<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapaianKinerjaOrganisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_capaian_kinerja_organisasi', function (Blueprint $table) {
            $table->id();
            $table->enum('capaian_kinerja', ['Istimewa', 'Baik', 'Butuh Perbaikan', 'Kurang', 'Sangat Kurang'])->nullable();
            $table->year('tahun')->nullable();
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
        Schema::dropIfExists('skp_capaian_kinerja_organisasi');
    }
}
