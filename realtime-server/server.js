const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    },
    transports: ['websocket', 'polling']
});

const PORT = process.env.PORT || 3001;
const HOST = process.env.HOST || '127.0.0.1';
const SECRET_KEY = process.env.REALTIME_SECRET || 'pemilos_secret_broadcast_key_2026';
const BROADCAST_INTERVAL_MS = parseInt(process.env.BROADCAST_INTERVAL_MS, 10) || 600;

// Internal Metrics & State Tracking
let connectedClients = 0;
let lastKnownPayload = null;
let lastBroadcastTimestamp = 0;
let pendingBroadcastTimer = null;

let stats = {
    broadcastsReceived: 0,
    broadcastsEmitted: 0,
    broadcastsCoalesced: 0,
    startedAt: new Date().toISOString()
};

/**
 * Memancarkan payload suara terbaru ke seluruh client WebSocket (aula, monitor, admin).
 */
function dispatchBroadcast(triggerReason) {
    if (pendingBroadcastTimer) {
        clearTimeout(pendingBroadcastTimer);
        pendingBroadcastTimer = null;
    }

    if (!lastKnownPayload) {
        return;
    }

    io.emit('voteUpdate', lastKnownPayload);
    lastBroadcastTimestamp = Date.now();
    stats.broadcastsEmitted++;

    console.log(`[Broadcast ${triggerReason}] Dispatched vote update to ${connectedClients} client(s) at ${new Date().toLocaleTimeString('id-ID')} (Total emitted: ${stats.broadcastsEmitted})`);
}

io.on('connection', (socket) => {
    connectedClients++;
    const clientIp = socket.handshake.address;
    console.log(`[Socket.io] Client connected: ${socket.id} (IP: ${clientIp}) | Total: ${connectedClients}`);

    // Jika sudah ada payload perolehan suara terkini, langsung kirim ke client yang baru terkoneksi
    if (lastKnownPayload) {
        socket.emit('voteUpdate', lastKnownPayload);
    }

    socket.on('disconnect', () => {
        connectedClients = Math.max(0, connectedClients - 1);
        console.log(`[Socket.io] Client disconnected: ${socket.id} | Total: ${connectedClients}`);
    });
});

/**
 * Health check & diagnostic metrics endpoint
 */
app.get('/health', (req, res) => {
    res.json({
        status: 'ok',
        service: 'Pemilos Realtime Broadcaster (Coalescing Engine)',
        clients_connected: connectedClients,
        broadcast_interval_ms: BROADCAST_INTERVAL_MS,
        uptime_seconds: Math.floor(process.uptime()),
        metrics: stats,
        has_cached_payload: lastKnownPayload !== null,
        timestamp: new Date().toISOString()
    });
});

/**
 * Internal broadcast endpoint dipanggil oleh Laravel (Wajib Localhost)
 * Dilengkapi Coalescing & Debounce Window untuk mencegah spike emisi saat ratusan siswa vote serentak.
 */
app.post('/broadcast', (req, res) => {
    // 1. Verifikasi IP Pengirim: Hanya izinkan dari localhost (127.0.0.1 / ::1)
    const remoteIp = req.socket.remoteAddress || req.ip;
    const isLocalhost = remoteIp === '127.0.0.1' || 
                        remoteIp === '::1' || 
                        remoteIp === '::ffff:127.0.0.1';

    if (!isLocalhost && process.env.ALLOW_REMOTE_BROADCAST !== 'true') {
        console.warn(`[Security Alert] Blocked non-localhost broadcast attempt from IP: ${remoteIp}`);
        return res.status(403).json({ error: 'Broadcast endpoint is restricted to localhost only.' });
    }

    // 2. Verifikasi Secret Key
    const authKey = req.headers['x-realtime-secret'] || req.headers['authorization'];
    if (authKey !== SECRET_KEY && authKey !== `Bearer ${SECRET_KEY}`) {
        return res.status(401).json({ error: 'Unauthorized secret key' });
    }

    const payload = req.body;
    if (!payload || typeof payload !== 'object') {
        return res.status(400).json({ error: 'Missing or invalid broadcast payload' });
    }

    stats.broadcastsReceived++;
    lastKnownPayload = payload;

    const now = Date.now();
    const elapsedSinceLastBroadcast = now - lastBroadcastTimestamp;

    // 3. Throttle / Coalescing Logic:
    // Jika interval waktu sejak broadcast terakhir sudah terpenuhi dan tidak ada pending timer,
    // langsung pancarkan seketika (Leading Edge - 0 latency untuk pemilih pertama).
    if (elapsedSinceLastBroadcast >= BROADCAST_INTERVAL_MS && !pendingBroadcastTimer) {
        dispatchBroadcast('Instant/Leading-Edge');
        return res.json({
            success: true,
            mode: 'instant',
            clients_reached: connectedClients,
            timestamp: new Date().toISOString()
        });
    }

    // Jika vote masuk dalam burst window (< BROADCAST_INTERVAL_MS), gabungkan (coalesce)
    // dan jadwalkan pengiriman pada trailing-edge dengan payload paling mutakhir.
    stats.broadcastsCoalesced++;

    if (!pendingBroadcastTimer) {
        const remainingDelay = Math.max(50, BROADCAST_INTERVAL_MS - elapsedSinceLastBroadcast);
        pendingBroadcastTimer = setTimeout(() => {
            dispatchBroadcast('Coalesced/Trailing-Edge');
        }, remainingDelay);
    }

    return res.json({
        success: true,
        mode: 'coalesced',
        coalesced_count: stats.broadcastsCoalesced,
        clients_reached: connectedClients,
        timestamp: new Date().toISOString()
    });
});

// Graceful process shutdown handler
function handleShutdown(signal) {
    console.log(`\n[Process] Received ${signal}. Gracefully shutting down Socket.io server...`);
    if (pendingBroadcastTimer) {
        clearTimeout(pendingBroadcastTimer);
        dispatchBroadcast('Final-Flush');
    }
    server.close(() => {
        console.log('[Process] HTTP/Socket server closed.');
        process.exit(0);
    });
}

process.on('SIGINT', () => handleShutdown('SIGINT'));
process.on('SIGTERM', () => handleShutdown('SIGTERM'));

server.listen(PORT, HOST, () => {
    console.log(`====================================================`);
    console.log(`🚀 Pemilos Realtime Broadcaster running on ${HOST}:${PORT}`);
    console.log(`📡 WebSocket ready | Coalescing Window: ${BROADCAST_INTERVAL_MS}ms`);
    console.log(`🛡️  Restricted to Localhost with Secret Authentication`);
    console.log(`====================================================`);
});
