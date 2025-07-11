<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkpEvaluasiPeriodikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_evaluasi_periodik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_kerja_id');
            $table->foreignId('periode_id');
            $table->string('rating_hasil_kerja')->nullable();
            $table->text('deskripsi_rating_hasil_kerja')->nullable();
            $table->string('rating_perilaku')->nullable();
            $table->text('deskripsi_rating_perilaku')->nullable();
            $table->enum('predikat', ['Istimewa', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang', 'Butuh Perbaikan', 'Sangat Baik'])->nullable();
            $table->unique(['rencana_kerja_id', 'periode_id']);
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
        Schema::dropIfExists('skp_evaluasi_periodik');
    }
}
