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
{
    Schema::create('karyawans', function (Blueprint $table) {
        $table->id('id_karyawan');
        $table->string('nama_karyawan');
        $table->string('email_karyawan')->unique();
        $table->unsignedBigInteger('id_divisi');
        $table->unsignedBigInteger('id_jabatan');
        $table->timestamps();

        $table->foreign('id_divisi')->references('id_divisi')->on('divisis')->onDelete('cascade');
        $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatans')->onDelete('cascade');
    });
}

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
