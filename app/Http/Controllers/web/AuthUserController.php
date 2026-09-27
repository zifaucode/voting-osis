<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WebSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $webSetting = \Illuminate\Support\Facades\Cache::rememberForever('pemilos_web_setting', function () {
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

        // Cari pemilih berdasarkan Username / NIS dan Kode Akses (role = 2 / pemilih)
        $user = User::where('nisn', $nisn)
            ->where('code_access', $codeAccess)
            ->where('role', 2)
            ->first();

        if (!$user) {
            FacadesSession::flash('error', 'Kombinasi Username dan Kode Akses tidak ditemukan. Pastikan data yang dimasukkan sesuai dengan kartu suara Anda.');
            return redirect()->route('login.user')->withInput($request->only('nisn'));
        }

        // Cegah pemilih yang sudah pernah memilih untuk login kembali
        if ($user->vote_status != 0 || $user->is_vote !== null) {
            FacadesSession::flash('error', 'Hak suara untuk Username ' . $user->nisn . ' (' . $user->name . ') sudah pernah digunakan. Setiap pemilih hanya dapat memilih 1 kali dan tidak dapat login kembali.');
            return redirect()->route('login.user')->withInput($request->only('nisn'));
        }

        // Matikan remember me (false) pada bilik suara lab komputer publik demi efisiensi I/O & keamanan sesi
        Auth::login($user, false);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    /**
     * Logout Pemilih dari bilik suara.
     */
    public function userLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.user')->with('success', 'Anda telah berhasil keluar dari bilik suara.');
    }
}
