<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'user_id',
        'nama_karyawan',
        'email_karyawan',
        'role',
        'id_divisi',
        'id_jabatan',
    ];

    public $timestamps = true;

    // 🔗 RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // 🔗 RELASI KE DIVISI (INI YANG ERROR TADI)
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    // 🔗 RELASI KE JABATAN
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function getRoleFixAttribute()
    {
        // prioritas user.role, fallback ke kolom karyawans.role (untuk kompatibilitas)
        return $this->user->role ?? $this->role;
    }

    public function getJabatanFixAttribute()
    {
        // prioritas relasi jabatan, fallback ke user.jabatan (string)
        return $this->jabatan->nama_jabatan ?? ($this->user->jabatan ?? null);
    }

    // =====================
    // HELPER ROLE
    // =====================
   public function isAdmin()
    {
        return $this->role_fix === 'admin';
    }

    public function isPimpinan()
    {
        return in_array($this->role_fix, ['admin', 'pimpinan']);
    }

    public function isStaff()
    {
        return $this->role_fix === 'staff';
    }
 }
