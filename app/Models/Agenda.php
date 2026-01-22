<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'keterangan',
        'status',
        'surat_masuk_id',
        'surat_keluar_id',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];
}
