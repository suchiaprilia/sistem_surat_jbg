<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisis';

    protected $fillable = [
        'parent_id',            // 🔥 WAJIB UNTUK DISPOSISI BERANTAI
        'surat_masuk_id',
        'dari_karyawan_id',
        'ke_karyawan_id',
        'instruksi',
        'batas_waktu',
        'status',
        'catatan'
    ];

    /**
     * =========================
     * RELASI UTAMA
     * =========================
     */
    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function dari()
    {
        return $this->belongsTo(Karyawan::class, 'dari_karyawan_id');
    }

    public function ke()
    {
        return $this->belongsTo(Karyawan::class, 'ke_karyawan_id');
    }

    /**
     * =========================
     * RELASI DISPOSISI BERANTAI
     * =========================
     */

    // disposisi sebelumnya
    public function parent()
    {
        return $this->belongsTo(Disposisi::class, 'parent_id');
    }

    // disposisi lanjutan
    public function children()
    {
        return $this->hasMany(Disposisi::class, 'parent_id');
    }
}
