<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->string('aksi', 20);              // create|update|delete|login|logout dll
            $table->string('modul', 50);             // surat_masuk|surat_keluar|agenda dll
            $table->unsignedBigInteger('data_id')->nullable(); // id data yang diubah
            $table->string('keterangan_user', 100)->nullable(); // sementara: System/Admin
            $table->text('keterangan')->nullable();  // catatan tambahan
            $table->string('ip', 45)->nullable();     // ipv4/ipv6
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['modul', 'aksi']);
            $table->index(['data_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
