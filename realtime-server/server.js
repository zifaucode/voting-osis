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
const SECRET_KEY = process.env.REALTIME_SECRET || 'pemilos_secret_broadcast_key_2026';

let connectedClients = 0;
let lastKnownPayload = null;

io.on('connection', (socket) => {
    connectedClients++;
    const clientIp = socket.handshake.address;
    console.log(`[Socket.io] Client connected: ${socket.id} (IP: ${clientIp}) | Total: ${connectedClients}`);

    // Jika ada payload terbaru, langsung kirimkan ke client yang baru terhubung
    if (lastKnownPayload) {
        socket.emit('voteUpdate', lastKnownPayload);
    }

    socket.on('disconnect', () => {
        connectedClients = Math.max(0, connectedClients - 1);
        console.log(`[Socket.io] Client disconnected: ${socket.id} | Total: ${connectedClients}`);
    });
});

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({
        status: 'ok',
        service: 'Pemilos Realtime Broadcaster',
        clients_connected: connectedClients,
        uptime_seconds: Math.floor(process.uptime()),
        timestamp: new Date().toISOString()
    });
});

// Internal broadcast endpoint dipanggil oleh Laravel (Wajib Localhost)
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

    const authKey = req.headers['x-realtime-secret'] || req.headers['authorization'];
    if (authKey !== SECRET_KEY && authKey !== `Bearer ${SECRET_KEY}`) {
        return res.status(401).json({ error: 'Unauthorized secret key' });
    }

    const payload = req.body;
    if (!payload) {
        return res.status(400).json({ error: 'Missing broadcast payload' });
    }

    lastKnownPayload = payload;

    // Broadcast instant ke seluruh client (layar proyektor, admin, dll)
    io.emit('voteUpdate', payload);

    console.log(`[Broadcast] Dispatched vote update to ${connectedClients} clients at ${new Date().toLocaleTimeString('id-ID')}`);

    return res.json({
        success: true,
        clients_reached: connectedClients,
        timestamp: new Date().toISOString()
    });
});

server.listen(PORT, '0.0.0.0', () => {
    console.log(`====================================================`);
    console.log(`🚀 Pemilos Realtime Broadcaster running on :${PORT}`);
    console.log(`📡 WebSocket ready | Health: http://localhost:${PORT}/health`);
    console.log(`====================================================`);
});
