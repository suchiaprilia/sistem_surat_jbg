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
       Schema::create('divisis', function (Blueprint $table) {
    $table->id('id_divisi');
    $table->string('nama_divisi');
    $table->string('kode_divisi')->nullable();
    $table->text('deskripsi')->nullable();
    $table->string('kepala_divisi')->nullable();
    $table->string('kontak')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisi');
    }
};
