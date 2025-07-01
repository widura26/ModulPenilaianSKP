<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefinisiOperationalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definisi_operationals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_kerja_id')->nullable()->constrained('skp_hasil_kerja')->onDelete('cascade');
            $table->foreignId('indikator_id')->nullable()->constrained('skp_indikators')->onDelete('cascade');
            $table->string('topik', 255);
            $table->string('sub_topik', 255);
            $table->text('deskripsi')->nullable();
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
        Schema::dropIfExists('definisi_operationals');
    }
}
