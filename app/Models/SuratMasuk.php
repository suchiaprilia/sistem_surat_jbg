<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $fillable = [
        'no_surat',
        'tanggal',
        'tanggal_terima',
        'penerima',
        'pengirim',
        'subject',
        'tujuan',
        'file_surat',
    ];
}

