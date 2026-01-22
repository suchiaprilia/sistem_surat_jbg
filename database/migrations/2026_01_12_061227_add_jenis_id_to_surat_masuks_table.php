<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // hanya tambah kolom jika belum ada
        if (!Schema::hasColumn('surat_masuks', 'id_jenis_surat')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->unsignedBigInteger('id_jenis_surat')
                      ->nullable()
                      ->after('subject');
            });
        }

        // ❌ JANGAN BUAT FOREIGN KEY DI SINI
        // foreign key SUDAH dibuat di migration lain
    }

    public function down(): void
    {
        if (Schema::hasColumn('surat_masuks', 'id_jenis_surat')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->dropColumn('id_jenis_surat');
            });
        }
    }
};
