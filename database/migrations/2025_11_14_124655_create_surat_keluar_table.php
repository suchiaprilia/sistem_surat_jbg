<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id('id_surat_keluar');

            $table->unsignedBigInteger('id_user')->nullable();

            $table->date('date');
            $table->string('no_surat_keluar'); // contoh: 388/AH/JBG/IX/2025

            $table->string('destination');
            $table->string('subject');

            $table->unsignedBigInteger('id_number_surat')->nullable(); // relasi ke nomor_surats

            $table->string('file_scan')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('signed_by')->nullable();

            // kalau kamu pakai jenis surat juga:
            $table->unsignedBigInteger('id_jenis_surat')->nullable();

            $table->timestamps();

            // index biar cepat
            $table->index(['date']);
            $table->index(['id_user']);
            $table->index(['id_number_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
