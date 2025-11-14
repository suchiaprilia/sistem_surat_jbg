
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
    Schema::create('surat_keluars', function (Blueprint $table) {
        $table->id('id_surat_keluar');
        $table->string('no_surat_keluar');
        $table->string('destination');
        $table->string('subject');
        $table->unsignedBigInteger('id_user');
        $table->date('date');
        $table->string('file_scan')->nullable();
        $table->string('requested_by')->nullable();
        $table->string('signed_by')->nullable();
        $table->unsignedBigInteger('id_number_surat');
        $table->timestamps();

        $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        $table->foreign('id_number_surat')->references('id_number_surat')->on('number_surats')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
