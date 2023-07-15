<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculoTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('vehiculo', function (Blueprint $table) {
        $table->string('VehCodi')->nullable();
        $table->string('VehPlac')->nullable();
        $table->string('VehMarc')->nullable();
        $table->string('VehInsc')->nullable();
        $table->string('empcodi');
        $table->primary('VehCodi');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('vehiculo');
    }
}
