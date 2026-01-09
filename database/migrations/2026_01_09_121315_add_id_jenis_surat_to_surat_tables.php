<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== TAMBAH KE SURAT MASUK =====
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jenis_surat')
                  ->nullable()
                  ->after('subject');

            $table->foreign('id_jenis_surat')
                  ->references('id_jenis_surat')
                  ->on('jenis_surat')
                  ->onDelete('set null');
        });

        // ===== TAMBAH KE SURAT KELUAR =====
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jenis_surat')
                  ->nullable()
                  ->after('subject');

            $table->foreign('id_jenis_surat')
                  ->references('id_jenis_surat')
                  ->on('jenis_surat')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropForeign(['id_jenis_surat']);
            $table->dropColumn('id_jenis_surat');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropForeign(['id_jenis_surat']);
            $table->dropColumn('id_jenis_surat');
        });
    }
};
