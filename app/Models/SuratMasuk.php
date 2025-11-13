<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuks'; // nama tabel
    protected $primaryKey = 'id_surat_masuk'; // primary key

    protected $fillable = [
        'no_surat_masuk',
        'from',
        'tujuan_email',
        'file_scan',
        'subject',
        'received_by',
        'id_user',
        'date',
        'tanda_tangan',
        'give_to',
        'status_disposisi'
    ];
}
