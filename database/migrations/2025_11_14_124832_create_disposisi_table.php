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
    Schema::create('disposisis', function (Blueprint $table) {
        $table->id('id_disposisi');
        $table->unsignedBigInteger('id_surat_masuk');
        $table->unsignedBigInteger('id_user');
        $table->date('tanggal_disposisi');
        $table->string('status');
        $table->text('intruksi');
        $table->timestamps();

        $table->foreign('id_surat_masuk')->references('id_surat_masuk')->on('surat_masuks')->onDelete('cascade');
       // $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};