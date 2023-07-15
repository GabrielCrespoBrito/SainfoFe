<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContingenciasDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contingencias_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('empcodi');
            $table->integer('con_id');
            $table->string('vtaoper');
            $table->string('tidcodi');
            $table->string('serie');
            $table->string('numero');
            $table->integer('motivo_id')->nullable();
            $table->string('gravada');
            $table->string('exonerada');
            $table->string('inafecta');
            $table->string('igv');
            $table->string('isc');
            $table->string('total');
            $table->string('tidcodi_ref')->nullable();
            $table->string('serie_ref')->nullable();
            $table->string('numero_ref')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contingencias_detalles');
    }
}
