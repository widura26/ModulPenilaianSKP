<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkpRealisasiPeriodikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_realisasi_periodik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_kerja_id');
            $table->foreignId('periode_id');
            $table->text('realisasi')->nullable();
            $table->text('bukti_dukung')->nullable();
            $table->unique(['hasil_kerja_id', 'periode_id']);
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
        Schema::dropIfExists('skp_realisasi_periodik');
    }
}
