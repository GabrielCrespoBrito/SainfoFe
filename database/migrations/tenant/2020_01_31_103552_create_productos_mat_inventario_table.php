<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosMatInventarioTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('productos_mat_inventario', function (Blueprint $table) {
      $table->increments('InvCodi');
      $table->string('InvNomb');
      $table->string('InvObse')->nullable();
      $table->date('InvFech')->nullable();
      $table->string('LocCodi')->nullable();
      $table->char('InvEsta', 1)->default('P');
      $table->string('empcodi')->nullable();
      $table->char('panano', 4)->nullable();
      $table->char('mescodi', 6)->nullable();
      $table->string('User_Crea')->nullable();
      $table->dateTime('User_FCrea')->nullable();
      $table->string('User_ECrea')->nullable();
      $table->string('User_Modi')->nullable();
      $table->dateTime('User_FModi')->nullable();
      $table->string('User_EModi')->nullable();
      $table->string('UDelete')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('productos_mat_inventario');
  }
}
