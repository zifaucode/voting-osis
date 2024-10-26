<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;

class AuthUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('question.user');
        }
        return view('user.auth.login', []);
    }

    public function register()
    {
        return view('user.auth.register', []);
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
        $email = $request->email;
        $password = $request->password;

        // dd($username, $password);

        Auth::attempt(['email' => $email, 'password' => $password]);
        if (Auth::check()) {
            $request->session()->regenerate();
            return redirect()->intended('/user/question');
        }
        FacadesSession::flash('error', 'Email atau password salah');
        return redirect()->route('login.user');
    }

    public function newRegister(Request $request)
    {
        try {
            DB::beginTransaction();
            $newQuestion = new User();
            $newQuestion->role = 2;
            $newQuestion->name = $request->name;
            $newQuestion->email = $request->email;
            $newQuestion->age = $request->age;
            $newQuestion->phone = $request->phone;
            $newQuestion->address = $request->address;
            $newQuestion->gender = $request->gender;
            $newQuestion->password = Hash::make($request->password);
            $newQuestion->save();
            DB::commit();
            return redirect()->route('register')->with('message', 'Selamat anda sudah terdaftar');
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal error',
                'code' => 500,
                'error' => true,
                'errors' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
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

    public function userLogout()
    {
        Auth::logout();
        return redirect()->route('login.user');
    }
}
