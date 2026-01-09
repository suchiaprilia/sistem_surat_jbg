<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuks';

    protected $fillable = [
        'no_surat',
        'tanggal',
        'tanggal_terima',
        'penerima',
        'pengirim',
        'subject',
        'tujuan',
        'file_surat',
        'id_jenis_surat'
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }
}
