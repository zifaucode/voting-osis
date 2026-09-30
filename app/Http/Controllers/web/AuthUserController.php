<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WebSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session as FacadesSession;

class AuthUserController extends Controller
{
    /**
     * Tampilkan halaman login pemilih dengan NIS dan Kode Akses.
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 1) {
                return redirect()->route('dashboard'); // Admin dashboard
            }

            // Jika pemilih sudah pernah memilih, langsung logout
            if (Auth::user()->vote_status != 0) {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                return redirect()->route('login.user')->with('error', 'Hak suara Anda telah digunakan. Anda tidak dapat login kembali.');
            }

            return redirect('/');
        }

        $webSetting = Cache::rememberForever('pemilos_web_setting', function () {
            return WebSetting::first();
        });

        return view('user.auth.login', [
            'web_setting' => $webSetting,
        ]);
    }

    /**
     * Autentikasi Pemilih menggunakan NIS dan Kode Akses.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'code_access' => 'required|string',
        ], [
            'nisn.required' => 'Username wajib diisi.',
            'code_access.required' => 'Kode akses pemilih wajib diisi.',
        ]);

        $nisn = trim($request->nisn);
        $codeAccess = trim($request->code_access);

        // Cari pemilih berdasarkan Username / NIS (role = 2 / pemilih)
        $user = User::where('nisn', $nisn)
            ->where('role', 2)
            ->first();

        // Verifikasi kode akses yang telah di-hash dengan Bcrypt (dengan fallback upgrade otomatis)
        $isCodeValid = false;
        if ($user && !empty($user->code_access)) {
            if (\Illuminate\Support\Facades\Hash::check($codeAccess, $user->code_access)) {
                $isCodeValid = true;
            } elseif ((string)$user->code_access === $codeAccess) {
                // Jika masih ada data legacy plain text, otomatis upgrade ke Bcrypt hash
                $user->code_access = \Illuminate\Support\Facades\Hash::make($codeAccess);
                $user->save();
                $isCodeValid = true;
            }
        }

        if (!$user || !$isCodeValid) {
            FacadesSession::flash('error', 'Kombinasi Username dan Kode Akses tidak ditemukan. Pastikan data yang dimasukkan sesuai dengan kartu suara Anda.');
            return redirect()->route('login.user')->withInput($request->only('nisn'));
        }

        // Cegah pemilih yang sudah pernah memilih untuk login kembali
        if ($user->vote_status != 0 || $user->is_vote !== null) {
            FacadesSession::flash('error', 'Hak suara untuk Username ' . $user->nisn . ' (' . $user->name . ') sudah pernah digunakan. Setiap pemilih hanya dapat memilih 1 kali dan tidak dapat login kembali.');
            return redirect()->route('login.user')->withInput($request->only('nisn'));
        }

        // 1. Cek sesi aktif di bilik lain via Cache (Non-blocking check)
        $activeSessionId = Cache::get('active_voter_' . $user->id);
        $activeSessionTime = Cache::get('active_voter_time_' . $user->id);

        if ($activeSessionId && $activeSessionId !== $request->session()->getId()) {
            if ($activeSessionTime && now()->diffInSeconds($activeSessionTime) < 300) {
                FacadesSession::flash('error', 'Akun pemilih ini sedang aktif dibuka di bilik suara lain. Harap selesaikan pemilihan di bilik tersebut, atau tunggu beberapa menit.');
                return redirect()->route('login.user')->withInput($request->only('nisn'));
            }
        }

        // 2. Proteksi Anti-Race Condition Konkuren: Gunakan Atomic Cache Lock (bukan DB Row Lock)
        // Mencegah 2 bilik mengeksekusi login pada milidetik yang sama persis tanpa membebani tabel database
        $loginLock = Cache::lock('voter_login_lock_' . $user->id, 5);

        if (!$loginLock->get()) {
            FacadesSession::flash('error', 'Akun pemilih ini sedang diproses masuk di bilik suara lain. Silakan coba sesaat lagi.');
            return redirect()->route('login.user')->withInput($request->only('nisn'));
        }

        try {
            // Verifikasi ganda status vote pemilih
            $user->refresh();
            if ($user->vote_status != 0 || $user->is_vote !== null) {
                FacadesSession::flash('error', 'Hak suara untuk Username ' . $user->nisn . ' (' . $user->name . ') sudah pernah digunakan. Setiap pemilih hanya dapat memilih 1 kali dan tidak dapat login kembali.');
                return redirect()->route('login.user')->withInput($request->only('nisn'));
            }

            // Login pemilih terlebih dahulu (agar ID sesi dimigrasikan oleh AuthGuard)
            Auth::login($user, false);
            $request->session()->regenerate();
            $finalSessionId = $request->session()->getId();

            // Daftarkan sesi bilik suara aktif selama 5 menit
            Cache::put('active_voter_' . $user->id, $finalSessionId, now()->addMinutes(5));
            Cache::put('active_voter_time_' . $user->id, now(), now()->addMinutes(5));

            return redirect()->intended('/');
        } finally {
            $loginLock->release();
        }
    }

    /**
     * Logout Pemilih dari bilik suara.
     */
    public function userLogout(Request $request)
    {
        if (Auth::check()) {
            \Illuminate\Support\Facades\Cache::forget('active_voter_' . Auth::id());
            \Illuminate\Support\Facades\Cache::forget('active_voter_time_' . Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.user')->with('success', 'Anda telah berhasil keluar dari bilik suara.');
    }
}
