<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('surat_masuk_id');
            $table->unsignedBigInteger('dari_karyawan_id')->nullable();
            $table->unsignedBigInteger('ke_karyawan_id')->nullable();

            $table->text('instruksi')->nullable();
            $table->date('batas_waktu')->nullable();

            $table->enum('status', ['baru', 'dibaca', 'diproses', 'selesai'])
                  ->default('baru');

            $table->text('catatan')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('surat_masuk_id')
                  ->references('id')
                  ->on('surat_masuks')
                  ->cascadeOnDelete();

            $table->foreign('dari_karyawan_id')
                  ->references('id_karyawan')
                  ->on('karyawans')
                  ->nullOnDelete();

            $table->foreign('ke_karyawan_id')
                  ->references('id_karyawan')
                  ->on('karyawans')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
