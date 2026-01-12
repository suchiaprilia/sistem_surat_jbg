<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('surat_masuks', function (Blueprint $table) {
        $table->unsignedBigInteger('id_jenis_surat')->nullable()->after('subject');
        $table->foreign('id_jenis_surat')
              ->references('id_jenis_surat')
              ->on('jenis_surat')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('surat_masuks', function (Blueprint $table) {
        $table->dropForeign(['id_jenis_surat']);
        $table->dropColumn('id_jenis_surat');
    });
}
};
