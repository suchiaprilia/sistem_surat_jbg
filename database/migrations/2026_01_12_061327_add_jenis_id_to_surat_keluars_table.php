<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ❌ JANGAN TAMBAH KOLOM
        // ❌ JANGAN TAMBAH FOREIGN KEY
        // id_jenis_surat SUDAH ADA di migration sebelumnya
    }

    public function down(): void
    {
        // tidak perlu apa-apa
    }
};
