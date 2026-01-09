<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat';
    protected $primaryKey = 'id_jenis_surat';

    protected $fillable = ['jenis_surat'];

    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class, 'id_jenis_surat');
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class, 'id_jenis_surat');
    }
}
