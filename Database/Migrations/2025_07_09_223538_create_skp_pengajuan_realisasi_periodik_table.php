<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkpPengajuanRealisasiPeriodikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_pengajuan_realisasi_periodik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_id');
            $table->foreignId('periode_id');
            $table->enum('status', ['Belum Ajukan Realisasi', 'Belum Dievaluasi', 'Sudah Dievaluasi']);
            $table->unique(['rencana_id', 'periode_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skp_pengajuan_realisasi_periodik');
    }
}
