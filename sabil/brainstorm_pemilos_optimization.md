# 🧠 Brainstorm: Optimasi Pemilos — Perspektif Skenario Real

## Profil Beban Sesungguhnya

```
┌─────────────────────────────────────────────────────────────┐
│  3 Kelas Lab × 40 PC = 120 Bilik Suara Aktif Simultan      │
│  ~1000 Siswa ÷ 120 Bilik = ~8-9 Rotasi per bilik           │
│  Siklus: Login → Baca Kandidat → Vote → Auto-Logout        │
│  Durasi satu siklus: ~30-60 detik per siswa                 │
│                                                              │
│  Peak burst: 120 login + 120 vote dalam window 10-15 detik  │
│  Ancaman eksternal: ❌ Hampir nol (internal sekolah)        │
│  Prioritas: ⚡ Performa & Stabilitas > 🔒 Rate Limiting     │
└─────────────────────────────────────────────────────────────┘
```

> [!IMPORTANT]
> Rate limiting di skenario ini justru **berbahaya**. 40 siswa di satu lab = 1 IP public yang sama. Rate limiter per-IP akan memblokir siswa ke-7 dalam 1 menit. **Kita buang rate limit approach dan fokus ke throughput optimization.**

---

## 1. Anatomi Critical Path — Di Mana Waktu Terbuang?

```mermaid
sequenceDiagram
    participant S as Siswa (Browser)
    participant N as Nginx
    participant P as PHP-FPM Worker
    participant DB as MySQL/SQLite
    participant C as Cache Driver
    participant WS as Node.js Socket.io

    S->>N: POST /user/auth (NIS + Kode)
    N->>P: FastCGI dispatch
    
    Note over P: 🔴 Bottleneck #1: Bcrypt Hash::check()
    Note over P: ~100-200ms CPU per verify
    
    P->>DB: SELECT * FROM users WHERE nisn=?
    P->>P: Hash::check(code, hashed) ← MAHAL
    
    Note over P: 🔴 Bottleneck #2: DB Transaction Lock
    P->>DB: BEGIN; SELECT ... FOR UPDATE
    P->>C: Cache::get(active_voter_{id})
    Note over C: 🔴 Bottleneck #3: File I/O
    P->>C: Cache::put(active_voter_{id})
    P->>DB: COMMIT
    
    P->>C: Session write (file I/O)
    Note over C: 🔴 Bottleneck #4: Session File Lock
    P-->>S: 302 Redirect → /
    
    S->>N: GET / (halaman voting)
    N->>P: Render view + Cache::remember(candidates)
    P-->>S: HTML
    
    S->>N: POST /user-choose (submit suara)
    N->>P: FastCGI dispatch
    P->>DB: BEGIN; SELECT ... FOR UPDATE (users)
    Note over P,DB: 🔴 Bottleneck #5: Row Lock Serialisasi
    Note over P,DB: 120 vote concurrent → antrian lock
    P->>DB: INSERT total_vote_users + UPDATE users
    P->>DB: COMMIT
    
    P->>WS: dispatch(BroadcastVoteUpdate)
    Note over WS: 🟡 Bottleneck #6: Per-Vote Query + HTTP POST
    Note over WS: 120 vote = 120× query DB + 120× POST Socket.io
    
    P->>C: Session invalidate (file delete)
    P-->>S: JSON {success}
```

---

## 2. Bottleneck Ranking & Dampak Nyata

| # | Bottleneck | Lokasi File | Dampak Saat 120 Concurrent | Severity |
|---|---|---|---|---|
| **1** | `Hash::check()` Bcrypt | [AuthUserController.php:69](file:///home/murtix/projects/web/web-osis-zidan/app/Http/Controllers/web/AuthUserController.php#L69) | 120 × ~150ms = **~18 detik total CPU** jika serialized. Satu worker PHP-FPM cuma handle ~6-7 login/detik | 🔴 **CRITICAL** |
| **2** | File Session Driver | [config/session.php:21](file:///home/murtix/projects/web/web-osis-zidan/config/session.php#L21) | 120 session create + 120 session destroy per rotasi. File lock contention pada `/tmp/sessions/` | 🔴 **CRITICAL** |
| **3** | File Cache Driver | `.env → CACHE_DRIVER=file` | `active_voter_*` read/write per login + voting + logout = **6 file I/O operasi per siklus × 120 bilik** | 🔴 **CRITICAL** |
| **4** | DB `lockForUpdate()` di Login | [AuthUserController.php:93-112](file:///home/murtix/projects/web/web-osis-zidan/app/Http/Controllers/web/AuthUserController.php#L93-L112) | Transaction lock pada tabel `users` saat login. 120 login concurrent = antrian lock. **Tapi** karena tiap siswa beda ID, lock contention sebenarnya rendah (row-level, bukan table-level). | 🟡 **MEDIUM** |
| **5** | DB `lockForUpdate()` di Vote | [UserSelectCandidateController.php:97-104](file:///home/murtix/projects/web/web-osis-zidan/app/Http/Controllers/UserSelectCandidateController.php#L97-L104) | Sama — row-level lock per user ID, beda user = beda row. **Sebenarnya sudah aman.** | 🟢 **LOW** |
| **6** | Broadcast per vote | [BroadcastVoteUpdate.php:28-31](file:///home/murtix/projects/web/web-osis-zidan/app/Jobs/BroadcastVoteUpdate.php#L28-L31) | Setiap vote → query DB aggregat (`withCount`) + HTTP POST ke Socket.io. 40 vote bersamaan = 40 query identik + 40 HTTP POST | 🟡 **MEDIUM** |
| **7** | `showOff()` tanpa cache | [HomeController.php:79-98](file:///home/murtix/projects/web/web-osis-zidan/app/Http/Controllers/web/HomeController.php#L79-L98) | Layar aula yang di-refresh = query DB setiap kali tanpa cache | 🟡 **MEDIUM** |

---

## 3. Enhancement Proposals — Prioritized

### Tier 1: Wajib Sebelum Hari-H (Dampak Besar, Risiko Rendah)

#### 🔧 E1. Switch Session & Cache Driver → `database`

**Masalah:** File-based session & cache di bawah 120 concurrent users = filesystem lock contention pada ext4/xfs. Setiap PHP-FPM worker yang mengakses session file akan saling block pada `flock()`.

**Solusi:** Pindahkan ke `database` driver. Tabel `sessions` dan `cache` sudah ada migration-nya.

```diff
# .env production
- SESSION_DRIVER=file
+ SESSION_DRIVER=database
- CACHE_DRIVER=file  
+ CACHE_DRIVER=database
```

> [!TIP]
> `database` driver di MySQL menggunakan row-level locking (InnoDB) — jauh lebih scalable dari file lock. Dan tidak butuh install Redis (simplify ops). Session table perlu migration `php artisan session:table && php artisan migrate`.

**Effort:** 5 menit config change  
**Impact:** 🔥 Menghilangkan filesystem bottleneck untuk session & cache sekaligus

---

#### 🔧 E2. Broadcast Debounce — Aggregate Vote Updates

**Masalah saat ini:**
```
Vote masuk → dispatch(BroadcastVoteUpdate) → query DB → HTTP POST ke Socket.io
Vote masuk → dispatch(BroadcastVoteUpdate) → query DB → HTTP POST ke Socket.io  
Vote masuk → dispatch(BroadcastVoteUpdate) → query DB → HTTP POST ke Socket.io
... × 40 dalam 5 detik = 40 query + 40 HTTP POST yang isinya HAMPIR IDENTIK
```

**Solusi: Coalesce di Node.js side.** Daripada PHP mengirim full payload per vote, PHP cukup kirim signal "ada vote baru" dan Node.js yang decide kapan query + broadcast:

**Opsi A (Simple):** Tambahkan debounce timer di Socket.io server — jika sudah menerima broadcast, tunggu 500ms-1s sebelum mengirim ke client. Jika ada broadcast baru dalam window itu, reset timer. Ini membuat 40 vote dalam 5 detik hanya menghasilkan ~5-6 broadcast ke layar aula, bukan 40.

**Opsi B (Lebih Efisien):** PHP hanya kirim lightweight signal (vote count delta: `+1 untuk candidate_id X`), Node.js meng-maintain counter in-memory dan broadcast periodically. DB query hanya saat startup dan recovery.

```
Sebelum:  40 vote → 40 DB query → 40 HTTP POST → 40 WebSocket emit
Sesudah:  40 vote → 40 lightweight signal → Node.js debounce → 5-6 emit
```

**Effort:** ~30 menit  
**Impact:** 🔥 Reduksi DB query 85%+ untuk realtime display, response time vote lebih cepat

---

#### 🔧 E3. Cache `showOff()` — Live Display Aula

**Masalah:** [`HomeController::showOff()`](file:///home/murtix/projects/web/web-osis-zidan/app/Http/Controllers/web/HomeController.php#L79-L98) melakukan `WebSetting::first()` dan `OsisChairmanCandidate::all()` langsung ke DB tanpa cache. Layar aula yang di-auto-refresh berulang kali = query DB berulang.

**Solusi:** Bungkus dengan cache seperti `index()` sudah lakukan:

```php
$webSetting = Cache::rememberForever('pemilos_web_setting', fn() => WebSetting::first());
$osisCandidate = Cache::remember('pemilos_candidates_list', 3600, fn() => OsisChairmanCandidate::all());
```

**Effort:** 5 menit  
**Impact:** 🟢 Eliminasi query DB yang tidak perlu saat layar aula ditampilkan

---

#### 🔧 E4. Tuning PHP-FPM Worker Count

**Saat ini:** `pm = static`, `pm.max_children = 35` (VPS 4GB RAM)

**Analisis:** Bcrypt `Hash::check()` memakan ~150ms CPU per call. Dengan 35 worker:
- Max throughput login = `35 worker ÷ 0.15s = ~233 login/detik` ← ini sudah SANGAT cukup untuk 120 bilik
- Memory per worker: ~30-40MB (Laravel + OPcache) × 35 = ~1.2GB
- Sisa RAM untuk MySQL + Node.js + OS: ~2.8GB ← sehat

**Verdict:** 35 worker **sudah optimal** untuk 4GB RAM. Jangan naikkan lebih dari 40 — swap thrashing lebih bahaya daripada kekurangan worker.

**Yang perlu dipastikan:**
1. **OPcache JIT aktif** — mempercepat Bcrypt computation ~20-30%
2. **`pm.max_requests = 1000`** — sudah ada, mencegah memory leak
3. **`listen.backlog = 2048`** — sudah ada, menampung burst queue

**Effort:** Verifikasi saja  
**Impact:** 🟢 Konfirmasi bahwa infrastruktur sudah siap

---

### Tier 2: Optimasi Lanjutan (Nice-to-Have)

#### 🔧 E5. Eliminasi Anti-Double Login DB Transaction saat Login

**Masalah:** Di [`AuthUserController::authenticate()`](file:///home/murtix/projects/web/web-osis-zidan/app/Http/Controllers/web/AuthUserController.php#L90-L112), ada `DB::transaction()` dengan `lockForUpdate()` saat login untuk mencegah double login. Ini sebenarnya **overkill** karena:
1. Cache `active_voter_*` sudah mencukupi sebagai mekanisme lock
2. DB transaction lock di sini tidak melindungi apa-apa — yang dilindungi adalah `vote_status`, bukan login itu sendiri
3. Bahkan tanpa mekanisme ini, double vote tetap dicegah oleh transaction lock di `store()` saat voting

**Proposal:** Simplifikasi menjadi cache-only check tanpa DB transaction lock saat login:

```php
// Cukup cek cache, tanpa DB::transaction lockForUpdate
$activeSessionId = Cache::get('active_voter_' . $user->id);
if ($activeSessionId && $activeSessionId !== $request->session()->getId()) {
    $activeTime = Cache::get('active_voter_time_' . $user->id);
    if ($activeTime && now()->diffInSeconds($activeTime) < 300) {
        // Masih aktif di bilik lain
        return redirect()->route('login.user')->with('error', '...');
    }
}
```

**Effort:** 15 menit refactor  
**Impact:** 🟡 Menghilangkan 1 DB transaction per login — free up lock contention window

---

#### 🔧 E6. Realtime Display Enhancement — Smooth Animations

**Konteks:** Layar proyektor di aula harus memukau secara visual saat vote masuk secara real-time.

Saat ini Socket.io event `voteUpdate` mengirim full payload. Frontend aula seharusnya:
1. **Animate** perubahan angka (count-up/count-down dengan easing)
2. **Animate** perubahan progress bar (smooth width transition)
3. **Pulse** efek visual saat vote baru masuk (micro-animation)
4. **Show** notifikasi "Suara baru masuk!" dengan fade-out

> [!NOTE]
> Ini tidak memerlukan perubahan backend — murni frontend enhancement pada view `frontend.dashboard.live-count` dan `frontend.dashboard.show-off`.

**Effort:** ~1-2 jam  
**Impact:** 🟡 WOW factor untuk siswa yang menonton di aula

---

#### 🔧 E7. Nginx `real_ip` dari Cloudflare

**Masalah:** Meskipun bukan untuk rate limiting, log Nginx masih mencatat IP Cloudflare proxy, bukan IP asli siswa.

**Mengapa tetap penting:** Jika ada insiden (misal 1 PC dikuasai siswa usil yang mengakses berulang), admin tidak bisa trace ke IP mana.

```nginx
# Tambahkan di block server {}
set_real_ip_from 103.21.244.0/22;
set_real_ip_from 103.22.200.0/22;
# ... (semua subnet Cloudflare)
real_ip_header CF-Connecting-IP;
real_ip_recursive on;
```

**Effort:** 10 menit  
**Impact:** 🟢 Audit trail akurat

---

### Tier 3: Arsitektural (Post-Event Improvement)

#### 🔧 E8. Queue Worker untuk Broadcast

**Saat ini:** `QUEUE_CONNECTION` di default `sync` (dari `.env.example`). Artinya `BroadcastVoteUpdate::dispatch()` **tidak benar-benar async** — job dieksekusi inline di request PHP yang sama.

Jika `QUEUE_CONNECTION=database`:
- Job ditulis ke tabel `jobs` → queue worker terpisah memproses
- Response vote ke siswa **instant** (tidak perlu tunggu Socket.io HTTP POST)
- Queue worker bisa debounce secara natural

**Tetapi** ini menambah 1 daemon yang harus di-supervise (`php artisan queue:work`). Untuk event 1 hari, mungkin overkill.

**Alternatif pragmatis:** Jika `QUEUE_CONNECTION=sync`, broadcast timeout sudah 1s — aman.

---

## 4. Matriks Prioritas Final

```
                        DAMPAK TINGGI
                            │
          ┌─────────────────┼─────────────────┐
          │   E1 (Driver)   │  E2 (Debounce)  │
          │   ⭐⭐⭐⭐⭐   │  ⭐⭐⭐⭐      │
          │   5 menit       │  30 menit       │
  EFFORT  │─────────────────┼─────────────────│ EFFORT
  RENDAH  │   E3 (showOff)  │  E5 (Simplify)  │ TINGGI
          │   ⭐⭐⭐        │  E6 (Animate)   │
          │   5 menit       │  1-2 jam        │
          │                 │                 │
          └─────────────────┼─────────────────┘
                            │
                        DAMPAK RENDAH
```

## 5. Recommended Execution Order

1. **E1** — Switch `SESSION_DRIVER` & `CACHE_DRIVER` ke `database` *(5 min, instant win)*
2. **E3** — Cache `showOff()` queries *(5 min, trivial)*
3. **E2** — Broadcast debounce di Node.js *(30 min, high-impact untuk aula display)*
4. **E5** — Simplifikasi login anti-double lock *(15 min, cleaner code)*
5. **E7** — Nginx real_ip Cloudflare *(10 min, audit trail)*
6. **E6** — Animate live count display *(1-2 jam, wow factor)*

> [!TIP]
> **Total effort: ~2-3 jam** untuk semua 6 enhancement. E1 + E3 bisa di-push ke Zidan hari ini, sisanya besok.
