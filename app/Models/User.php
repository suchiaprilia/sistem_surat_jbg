<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';   // 🔑 WAJIB
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'jabatan',
        'role',
        'email',
        'password',
    ];

    protected $hidden = ['password'];

    public $timestamps = true;

    // 🔗 RELASI KE KARYAWAN
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id', 'id_user');
    }
}
