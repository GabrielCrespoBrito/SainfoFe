<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContingenciasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contingencias_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->string('empcodi');
            $table->string('panano')->nullable();
            $table->string('panperi')->nullable();
            $table->string('mescodi')->nullable();
            $table->string('docnume')->nullable();
            $table->string('ticket')->nullable();
            $table->date('fecha_documento');
            $table->dateTime('fecha_emision');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contingencias_cab');
    }
}
