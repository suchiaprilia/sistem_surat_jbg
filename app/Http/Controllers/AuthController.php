<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;

class AuthController extends Controller
{
    // menampilkan halaman login
    public function index()
    {
        // kalau sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // proses login (MD5)
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:3'],
        ]);

        $user = User::where('email', $request->email)
            ->where('password', md5($request->password))
            ->first();

        // ❌ login gagal (catat audit)
        if (!$user) {
            AuditLog::tulis(
                'login',
                'auth',
                null,
                'Login gagal untuk email: ' . $request->email
            );

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        // ✅ login sukses
        Auth::login($user);

        // session tambahan untuk tampilan
        session([
            'role'  => $user->role,
            'email' => $user->email,
            'nama'  => $user->nama,
        ]);

        // catat audit login sukses
        AuditLog::tulis(
            'login',
            'auth',
            $user->id_user ?? null,
            'Login berhasil'
        );

        return redirect()->route('dashboard');
    }

    // logout
    public function logout(Request $request)
    {
        // catat audit logout dulu (sebelum Auth::logout)
        AuditLog::tulis(
            'logout',
            'auth',
            Auth::user()->id_user ?? null,
            'Logout'
        );

        Auth::logout();

        // hapus session tambahan yang kamu pakai
        $request->session()->forget(['role', 'email', 'nama']);

        // reset session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil logout.');
    }
}
