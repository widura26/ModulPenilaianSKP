<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLampiransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lampirans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rencana_id')->nullable();
            $table->enum('jenis_lampiran', ['Dukungan Sumber Daya', 'Skema Pertanggung Jawaban', 'Konsekuensi']);
            $table->text('deskripsi_lampiran')->nullable();
            $table->timestamps();

            $table->foreign('rencana_id')->references('id')->on('skp_rencana_kerja')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lampirans');
    }
}
