<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCascadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('skp_cascading');
        Schema::create('skp_cascading', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_kerja_id')->nullable();
            $table->foreignId('indikator_id')->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->timestamps();

            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skp_cascading');
    }
}
