<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
{
    Schema::create('agendas', function (Blueprint $table) {
        $table->id('id_agenda');
        $table->unsignedBigInteger('id_surat_masuk');
        $table->unsignedBigInteger('id_surat_keluar');
        $table->date('tanggal_agenda');
        $table->string('jenis');
        $table->text('keterangan')->nullable();
        $table->timestamps();

        $table->foreign('id_surat_masuk')->references('id_surat_masuk')->on('surat_masuks')->onDelete('cascade');
        $table->foreign('id_surat_keluar')->references('id_surat_keluar')->on('surat_keluars')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};