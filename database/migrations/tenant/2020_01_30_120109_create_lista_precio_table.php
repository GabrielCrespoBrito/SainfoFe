<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaPrecioTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lista_precio', function (Blueprint $table) {
      $table->string('LisCodi');
      $table->string('LisNomb');
      $table->string('empcodi');
      $table->string('LocCodi');
      $table->primary(['LisCodi', 'empcodi', 'LocCodi'], 'primary_full');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('lista_precio');
  }
}
