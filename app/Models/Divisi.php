<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisis';
    protected $primaryKey = 'id_divisi';

    protected $fillable = [
        'nama_divisi',
        'kode_divisi',
        'deskripsi',
        'kepala_divisi',
        'kontak',
    ];
}
