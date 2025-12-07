<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');                // No Surat
            $table->date('tanggal');                   // Tanggal Surat
            $table->date('tanggal_terima');            // Tanggal Terima Surat
            $table->string('penerima');                // Penerima
            $table->string('pengirim');                // Pengirim
            $table->string('subject');                 // Subject
            $table->string('tujuan');                  // Tujuan
            $table->string('file_surat')->nullable();  // File Surat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
