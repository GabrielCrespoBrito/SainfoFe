<?php

use App\Models\MedioPago\MedioPago;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediosPagoTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('medios_pagos', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('tipo_pago');
      $table->integer('uso')->default("1")->nullable();
      $table->integer('default')->default(MedioPago::NO_DEFAULT)->nullable();
      $table->string('empcodi')->nullable();      
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
    Schema::dropIfExists('medios_pagos');
  }
}
