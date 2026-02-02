<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';          // tabel kamu
    protected $primaryKey = 'id_user';  // primary key kamu

    protected $fillable = [
        'nama',
        'jabatan',
        'role',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = true;
}
