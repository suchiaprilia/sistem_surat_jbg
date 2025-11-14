<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluars';
    protected $primaryKey = 'id_surat_keluar';

    protected $fillable = [
        'no_surat_keluar',
        'destination',
        'subject',
        'id_user',
        'date',
        'file_scan',
        'requested_by',
        'signed_by',
        'id_number_surat'
    ];
}
