<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluars';
    protected $primaryKey = 'id_surat_keluar';

    protected $fillable = [
        'no_surat_keluar',
        'destination',
        'subject',
        'date',
        'file_scan',
        'requested_by',
        'signed_by',
        'id_user',
        'id_number_surat',
        'id_jenis_surat'
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }
}
