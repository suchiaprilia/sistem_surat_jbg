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
    Schema::create('surat_masuks', function (Blueprint $table) {
        $table->id('id_surat_masuk');
        $table->string('no_surat_masuk');
        $table->string('from');
        $table->string('tujuan_email');
        $table->string('file_scan')->nullable();
        $table->string('subject');
        $table->string('received_by');
        $table->unsignedBigInteger('id_user');
        $table->date('date');
        $table->string('tanda_tangan')->nullable();
        $table->string('give_to')->nullable();
        $table->string('status_disposisi')->nullable();
        $table->timestamps();

      //  $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
