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
        'id_user',
        'date',
        'no_surat_keluar',
        'destination',
        'subject',
        'id_number_surat',
        'file_scan',
        'requested_by',
        'signed_by',
        'id_jenis_surat',
    ];

    public function nomorSurat()
    {
        return $this->belongsTo(NomorSurat::class, 'id_number_surat', 'id');
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }
}
