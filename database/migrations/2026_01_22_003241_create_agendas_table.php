<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();

            $table->string('judul');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();

            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();

            $table->enum('status', ['terjadwal', 'selesai', 'ditunda', 'dibatalkan'])
                ->default('terjadwal');

            // Relasi (opsional) ke surat masuk/keluar
            $table->unsignedBigInteger('surat_masuk_id')->nullable();
            $table->unsignedBigInteger('surat_keluar_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            // Index untuk query cepat
            $table->index(['tanggal_mulai', 'status']);
            $table->index(['surat_masuk_id']);
            $table->index(['surat_keluar_id']);

            // Foreign key (aktifkan kalau tabelnya memang ada)
            // $table->foreign('surat_masuk_id')->references('id')->on('surat_masuk')->nullOnDelete();
            // $table->foreign('surat_keluar_id')->references('id')->on('surat_keluar')->nullOnDelete();
            // $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
