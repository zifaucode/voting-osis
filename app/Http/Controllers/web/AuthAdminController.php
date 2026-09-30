<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;

class AuthAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $webSetting = \Illuminate\Support\Facades\Cache::rememberForever('pemilos_web_setting', function () {
            return \App\Models\WebSetting::first();
        });

        return view('admin.auth.login', [
            'web_setting' => $webSetting,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username administrator wajib diisi.',
            'password.required' => 'Kata sandi administrator wajib diisi.',
        ]);

        $username = trim((string)$request->username);
        $password = (string)$request->password;

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            // Pastikan akun memiliki hak akses administrator (role = 1)
            if (Auth::user()->role != 1) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Akses ditolak. Akun Anda tidak memiliki hak akses administrator.');
            }

            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return redirect()->route('login')
            ->withInput($request->only('username'))
            ->with('error', 'Username atau kata sandi administrator salah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
