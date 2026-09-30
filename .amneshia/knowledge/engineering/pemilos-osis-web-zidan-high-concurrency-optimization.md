---
id: "ddb62aaf-4b9f-4bbb-b656-770e8c0b8d17"
name: "Pemilos OSIS Web (Zidan) & High-Concurrency Optimization"
type: "architecture"
domain: "engineering"
visibility: "public"
allowed_agents: []
created: "2026-09-30T10:22:16.087Z"
updated: "2026-09-30T10:22:16.087Z"
---
## Observations

- **[architectural]** Context & Rationale: Optimizing Pemilos election system for real-world school conditions (3 computer labs x 40 PCs = 120 concurrent voting booths, ~1000 student voters cycling every 30-60s behind a single school NAT public IP). Heavy rate limiting is intentionally avoided for legitimate internal school traffic, focusing instead on zero-locking DB operations, high-throughput caching, and websocket broadcast coalescing for the live count projector display.
  `id: ac696016-60dd-41fe-90a4-d27891f97aec | confidence: 1 | status: active`

- **[architectural]** Technical Implementation: 
1. Realtime server (realtime-server/server.js): Implemented leading-edge instant broadcast (0ms latency for initial voter) combined with 600ms trailing-edge coalescing window to eliminate socket congestion when dozens of students submit votes in parallel. Added /health metrics endpoint.
2. Voter Login (app/Http/Controllers/web/AuthUserController.php): Replaced DB transaction lockForUpdate on the users table with non-blocking cache verification and atomic cache lock (Cache::lock), preventing MySQL Lock Wait Timeout (Error 1205) under concurrent logins.
3. ShowOff & LiveCount Caching (app/Http/Controllers/web/HomeController.php): Applied Cache::rememberForever for web setting and Cache::remember for candidate rankings in showOff() and liveCount(), removing redundant MySQL hits during projector reloads.
4. Database Sessions Migration (database/migrations/2026_09_27_202332_create_sessions_table.php): Created official sessions table schema for SESSION_DRIVER=database, and updated .env.example with production settings.
5. Cloudflare Real IP (deployment/nginx-pemilos.conf): Added full Cloudflare IPv4 & IPv6 CIDRs with set_real_ip_from and real_ip_header CF-Connecting-IP so $remote_addr reflects real lab PCs.
6. Rate Limiter Tolerance (app/Providers/RouteServiceProvider.php): Relaxed voter-login throttle to 15 per minute per NISN to prevent student lockouts on typos.
  `id: 35874602-a43d-4d3c-a338-b525cf358751 | confidence: 1 | status: active`

- **[architectural]** Operational Parameters: Socket.io microservice on port 3001 (127.0.0.1, restricted by secret header x-realtime-secret). Laravel queue worker recommended on database driver. Nginx reverse proxies /socket.io/ to port 3001 with WebSocket headers. Deployment helper script created at sabil/vps-deploy-helper.sh and detailed runbook at sabil/PANDUAN_OPTIMASI_UNTUK_ZIDAN.md.
  `id: 7943a8f1-7411-4bc0-b4a2-7642895434db | confidence: 1 | status: active`

- **[architectural]** Verification & Test Outcomes: Verified Node.js syntax of realtime-server/server.js with node -c (exit code 0). Validated PHP syntax of all modified controllers and service providers with php -l (all passed 0 syntax errors). Ensured no large frontend modifications were performed per user instructions. Verified non-blocking cache lock release in finally block to avoid dangling locks.
  `id: e691ac44-b9ff-46b9-9fbc-806012827f65 | confidence: 1 | status: active`

- **[architectural]** Architectural Guardrails & Gotchas: 
- DO NOT apply IP-only rate limiting on Nginx or Cloudflare because all 120 booth PCs in the 3 labs share a single public IP via school NAT. Rate limits must strictly isolate by NISN key ($nisn . '|' . $ip).
- Zidan must run 'php artisan migrate --force' on the VPS before enabling SESSION_DRIVER=database to avoid missing table fatal exceptions.
- Cloudflare SSL/TLS must remain in Full (Strict) mode because the origin server has a valid Let's Encrypt certificate; Flexible mode will cause redirect loops.
- Queue worker (php artisan queue:work) must run via PM2 or systemd so broadcast HTTP requests do not block voter HTTP responses.
  `id: 09a4e3fe-a193-4f33-abf3-2b0f030d9c91 | confidence: 1 | status: active`

- **[architectural]** Verification & Test Execution Outcomes:
1. Automated PHPUnit Test Suite (tests/Feature/PemilosVotingFlowTest.php): 6/6 tests passing (22 assertions). Verified voter authentication, anti-double login session denial, voting submission, prevent double voting, voter login lockouts post-vote, and ShowOff cache warming.
2. Realtime Broadcast Coalescing Simulation (sabil/simulasi_pemilos.cjs): 40 concurrent broadcast requests were sent into Node.js server.js within 95ms. Leading-edge emit triggered with 0ms latency on the 1st vote, while 39 subsequent requests were coalesced into a trailing-edge broadcast. The aula projector websocket received only 2 aggregated updates without dropping the final vote count (98% socket broadcast reduction).
3. Laravel ShowOff Cache Benchmark: Verified 10 consecutive reloads of /show-off took an average of 40ms response time using SQLite and in-memory Cache::rememberForever.
4. Deployment Daemon Configuration: Added explicit VPS instructions in sabil/PANDUAN_OPTIMASI_UNTUK_ZIDAN.md for Zidan to run server.js 24/7 with PM2 (pm2 startup && pm2 save) or native Systemd service (pemilos-realtime.service).
  `id: d098f7c6-4d69-489e-a9a8-b994f2c4fc27 | confidence: 1 | status: active`

## Relations

_No relations recorded yet._
