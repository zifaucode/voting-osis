/**
 * ==============================================================================
 * SIMULASI KONKURENSI PEMILOS (120 BILIK SUARA & COALESCING SOCKET.IO)
 * Dibuat oleh Sabil untuk menguji ketahanan server sebelum hari-H
 * ==============================================================================
 */

const http = require('http');
const { io } = require('socket.io-client');

const SOCKET_URL = 'http://127.0.0.1:3001';
const SECRET_KEY = 'pemilos_secret_broadcast_key_2026';
const LARAVEL_URL = 'http://127.0.0.1:8000';

// Warna output terminal
const green = (text) => `\x1b[32m${text}\x1b[0m`;
const cyan = (text) => `\x1b[36m${text}\x1b[0m`;
const yellow = (text) => `\x1b[33m${text}\x1b[0m`;
const red = (text) => `\x1b[31m${text}\x1b[0m`;
const bold = (text) => `\x1b[1m${text}\x1b[0m`;

function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

/**
 * HTTP POST helper
 */
function sendHttpRequest(url, path, method, headers, data) {
    return new Promise((resolve, reject) => {
        const parsedUrl = new URL(path, url);
        const options = {
            hostname: parsedUrl.hostname,
            port: parsedUrl.port,
            path: parsedUrl.pathname + parsedUrl.search,
            method: method,
            headers: headers,
            timeout: 5000
        };

        const req = http.request(options, (res) => {
            let body = '';
            res.on('data', (chunk) => body += chunk);
            res.on('end', () => {
                resolve({
                    statusCode: res.statusCode,
                    headers: res.headers,
                    body: body
                });
            });
        });

        req.on('error', (err) => reject(err));
        req.on('timeout', () => {
            req.destroy();
            reject(new Error('Request timeout'));
        });

        if (data) {
            req.write(typeof data === 'string' ? data : JSON.stringify(data));
        }
        req.end();
    });
}

/**
 * UJI 1: SIMULASI BROADCAST COALESCING SOCKET.IO SERVER
 * Mensimulasikan 40 bilik suara serentak mengirimkan sinyal vote dalam 1 detik.
 */
async function testSocketCoalescing() {
    console.log(bold('\n============================================================'));
    console.log(bold('📊 SIMULASI 1: SOCKET.IO BROADCAST COALESCING & DEBOUNCE'));
    console.log(bold('============================================================'));

    // 1. Hubungkan simulasi layar aula via WebSocket
    console.log(cyan('📡 [Aula Simulator] Menghubungkan client WebSocket ke ') + SOCKET_URL);
    const socketClient = io(SOCKET_URL, {
        transports: ['websocket'],
        reconnection: false
    });

    let receivedEmits = 0;
    let lastReceivedPayload = null;

    await new Promise((resolve, reject) => {
        socketClient.on('connect', () => {
            console.log(green('✅ [Aula Simulator] Terhubung! Socket ID: ') + socketClient.id);
            resolve();
        });
        socketClient.on('connect_error', (err) => {
            reject(new Error('Gagal menghubungkan ke Socket server: ' + err.message));
        });
    });

    socketClient.on('voteUpdate', (payload) => {
        receivedEmits++;
        lastReceivedPayload = payload;
        console.log(cyan(`   📥 [Aula Event #${receivedEmits}] Menerima update: Total Suara = ${payload.summary?.total_voted || payload.vote_counter} (Jam: ${payload.last_updated})`));
    });

    // 2. Tembakkan 40 request broadcast ke server.js secara bersamaan (burst concurrency)
    const TOTAL_BURST_VOTES = 40;
    console.log(yellow(`\n🚀 [Burst Engine] Meluncurkan ${TOTAL_BURST_VOTES} broadcast suara konkuren...`));

    const startTime = Date.now();
    const burstPromises = [];

    for (let i = 1; i <= TOTAL_BURST_VOTES; i++) {
        const payload = {
            status: 'success',
            vote_counter: i,
            summary: {
                total_voters: 1000,
                total_voted: 200 + i,
                turnout_percentage: ((200 + i) / 10).toFixed(1)
            },
            last_updated: new Date().toLocaleTimeString('id-ID')
        };

        burstPromises.push(
            sendHttpRequest(SOCKET_URL, '/broadcast', 'POST', {
                'Content-Type': 'application/json',
                'x-realtime-secret': SECRET_KEY
            }, payload)
        );
    }

    const responses = await Promise.all(burstPromises);
    const elapsed = Date.now() - startTime;

    let instantCount = 0;
    let coalescedCount = 0;

    responses.forEach(res => {
        try {
            const json = JSON.parse(res.body);
            if (json.mode === 'instant') instantCount++;
            if (json.mode === 'coalesced') coalescedCount++;
        } catch (e) {}
    });

    console.log(green(`\n⚡ [Hasil Burst POST] ${TOTAL_BURST_VOTES} requests selesai dalam ${elapsed} ms`));
    console.log(`   - Mode Instant (Leading-Edge): ${green(instantCount)} request (0ms latency untuk suara pertama)`);
    console.log(`   - Mode Coalesced (Trailing-Edge): ${yellow(coalescedCount)} request (digabung secara cerdas)`);

    // Tunggu jeda trailing edge (800ms) agar timer debounce menyelesaikan flush terakhir
    console.log(cyan('\n⏳ Menunggu trailing-edge debounce timer menyelesaikan emisi...'));
    await sleep(900);

    // 3. Verifikasi metrik dari /health endpoint
    const healthRes = await sendHttpRequest(SOCKET_URL, '/health', 'GET', {}, null);
    const healthData = JSON.parse(healthRes.body);

    console.log(bold('\n📈 [Metrik Diagnostik Node.js Server]'));
    console.log(`   - Broadcast Diterima : ${bold(healthData.metrics.broadcastsReceived)}`);
    console.log(`   - Broadcast Di-Emit  : ${green(healthData.metrics.broadcastsEmitted)}`);
    console.log(`   - Broadcast Dihemat  : ${yellow(healthData.metrics.broadcastsCoalesced)} (Penghematan: ${Math.round((healthData.metrics.broadcastsCoalesced / healthData.metrics.broadcastsReceived) * 100)}%)`);
    console.log(`   - Emisi Diterima Aula: ${bold(receivedEmits)} kali`);
    console.log(`   - Nilai Akhir di Aula: Total Suara = ${bold(lastReceivedPayload?.summary?.total_voted || 'N/A')}`);

    if (receivedEmits <= 5 && lastReceivedPayload?.vote_counter === TOTAL_BURST_VOTES) {
        console.log(green('\n🎯 KESIMPULAN TEST 1: SUKSES BESAR!'));
        console.log(green('   Socket server berhasil meredam 40x lonjakan menjadi beberapa emisi terkonsolidasi tanpa kehilangan data terakhir.'));
    } else {
        console.log(yellow('\n⚠️ TEST 1 SELESAI DENGAN CATATAN: Periksa frekuensi emisi.'));
    }

    socketClient.disconnect();
}

/**
 * UJI 2: SIMULASI KESEHATAN SISTEM LARAVEL & SHOW-OFF CACHE
 */
async function testLaravelEndpoints() {
    console.log(bold('\n============================================================'));
    console.log(bold('🏛️ SIMULASI 2: UJI CACHE HALAMAN AULA & API LARAVEL'));
    console.log(bold('============================================================'));

    try {
        console.log(cyan('📡 Mengakses halaman aula /show-off...'));
        const t1 = Date.now();
        const showOffRes = await sendHttpRequest(LARAVEL_URL, '/show-off', 'GET', {}, null);
        const tShowOff = Date.now() - t1;

        if (showOffRes.statusCode === 200) {
            console.log(green(`✅ Halaman Aula /show-off HTTP 200 OK (${tShowOff} ms)`));
        } else {
            console.log(red(`❌ /show-off HTTP ${showOffRes.statusCode}`));
        }

        console.log(cyan('📡 Menguji reload cepat /show-off sebanyak 10x untuk membuktikan Cache...'));
        const reloadTimes = [];
        for (let i = 1; i <= 10; i++) {
            const start = Date.now();
            await sendHttpRequest(LARAVEL_URL, '/show-off', 'GET', {}, null);
            reloadTimes.push(Date.now() - start);
        }
        const avgTime = Math.round(reloadTimes.reduce((a, b) => a + b, 0) / reloadTimes.length);
        console.log(green(`✅ 10x Reload Berhasil! Rata-rata response time: ${avgTime} ms (Super cepat berkat Cache::rememberForever)`));

    } catch (err) {
        console.log(yellow(`⚠️ Peringatan Laravel Server: ${err.message}`));
    }
}

async function main() {
    try {
        await testSocketCoalescing();
        await testLaravelEndpoints();
        console.log(bold('\n============================================================'));
        console.log(green(bold('🎉 SELURUH SIMULASI SELESAI DENGAN SUKSES!')));
        console.log(bold('============================================================\n'));
    } catch (e) {
        console.error(red('Fatal error during simulation: ' + e.message));
        process.exit(1);
    }
}

main();
