<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'aksi',
        'modul',
        'data_id',
        'keterangan_user',
        'keterangan',
        'ip',
        'user_agent',
    ];

    // helper biar gampang panggil dari controller
   public static function tulis(string $aksi, string $modul, $dataId = null, string $keterangan = null, string $user = null): void
{
    try {
        // ambil user login jika ada
        $authUser = \Illuminate\Support\Facades\Auth::user();

        // kalau parameter $user tidak dikirim, otomatis pakai user login
        if (!$user) {
            if ($authUser) {
                $nama = $authUser->nama ?? ($authUser->name ?? 'User');
                $role = $authUser->role ?? '';
                $user = trim($nama . ($role ? " ($role)" : ''));
            } else {
                $user = 'Guest';
            }
        }

        self::create([
            'aksi'            => $aksi,
            'modul'           => $modul,
            'data_id'         => $dataId,
            'keterangan_user' => $user,
            'keterangan'      => $keterangan,
            'ip'              => request()->ip(),
            'user_agent'      => request()->userAgent(),
        ]);
    } catch (\Throwable $e) {
        // biarin aja supaya fitur utama tidak gagal
    }
}

}
