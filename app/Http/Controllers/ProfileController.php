<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Karyawan;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Silakan login dulu.');
        }

        $email = Auth::user()->email;

        $user = User::where('email', $email)->first();

        $karyawan = Karyawan::with(['divisi', 'jabatan'])
            ->where('email_karyawan', $email)
            ->first();

        return view('profile', compact('user', 'karyawan'));
    }

    public function updatePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Silakan login dulu.');
        }

        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:4',
            'konfirmasi' => 'required|same:password_baru',
        ]);

        $email = Auth::user()->email;

        $user = User::where('email', $email)->first();
        if (!$user) return back()->with('error', 'User tidak ditemukan.');

        if ($user->password !== md5($request->password_lama)) {
            return back()->with('error', 'Password lama salah.');
        }

        $user->password = md5($request->password_baru);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
