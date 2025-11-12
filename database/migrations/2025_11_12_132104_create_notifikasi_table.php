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
    Schema::create('notifikasis', function (Blueprint $table) {
        $table->id('id_notifikasi');
        $table->unsignedBigInteger('id_surat_masuk');
        $table->string('email_tujuan');
        $table->text('pesan');
        $table->timestamps();

        $table->foreign('id_surat_masuk')->references('id_surat_masuk')->on('surat_masuks')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
