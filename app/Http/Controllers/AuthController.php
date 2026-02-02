<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // menampilkan halaman login
    public function index()
    {
        // kalau sudah login, redirect ke halaman lain (ubah sesuai kebutuhan)
        if (Auth::check()) {
            return redirect()->route('dashboard'); // nanti kamu buat route dashboard
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
            // dd(md5($request->password));

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        Auth::login($user); // pakai session auth Laravel
session([
          'role' => $user->role,
    'email' => $user->email,
    'nama' => $user->nama,
    ]);
        // arahkan setelah login (ubah sesuai kebutuhan)
        return redirect()->route('dashboard');
    }

   public function logout(Request $request)
{
    Auth::logout(); // keluar dari login

    // hapus session role (kalau kamu pakai)
    $request->session()->forget(['role', 'user_name']);

    // reset session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home')->with('success', 'Berhasil logout.');
}
}
