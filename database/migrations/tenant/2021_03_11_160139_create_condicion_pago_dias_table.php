<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCondicionPagoDiasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('condicion_pago_dias', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('PgoCodi');
      $table->string('PgoDias');
      $table->string('ConCodi');
      $table->string('empcodi');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('condicion_pago_dias');
  }
}
