<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\AuditLog;

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

        // ✅ AUDIT: view profile
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            AuditLog::tulis(
                'view_profile',
                'profile',
                Auth::id() ?? null,
                'Melihat halaman profile',
                $actor
            );
        }

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

        // ❌ Password lama salah
        if ($user->password !== md5($request->password_lama)) {
            if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
                $actor = Auth::user()->name ?? 'System';
                AuditLog::tulis(
                    'change_password_failed',
                    'profile',
                    Auth::id() ?? null,
                    'Gagal mengubah password: password lama salah',
                    $actor
                );
            }

            return back()->with('error', 'Password lama salah.');
        }

        $user->password = md5($request->password_baru);
        $user->save();

        // ✅ Password berhasil diubah
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            AuditLog::tulis(
                'change_password_success',
                'profile',
                Auth::id() ?? null,
                'Berhasil mengubah password',
                $actor
            );
        }

        return back()->with('success', 'Password berhasil diubah.');
    }
}
