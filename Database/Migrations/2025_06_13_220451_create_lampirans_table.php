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
            $table->enum('jenis_lampiran', ['Dukungan Sumber Daya', 'Skema Pertanggung Jawaban', 'Konsekuensi']);
            $table->text('deskripsi_lampiran')->nullable();
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
        Schema::dropIfExists('lampirans');
    }
}
