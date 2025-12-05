<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';

    protected $primaryKey = 'id_jenis_surat';

    public $timestamps = true;

    protected $fillable = ['jenis_surat'];
}
