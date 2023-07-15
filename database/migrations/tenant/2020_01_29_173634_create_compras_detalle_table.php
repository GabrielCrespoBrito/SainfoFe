<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasDetalleTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('compras_detalle', function (Blueprint $table) {
      $table->string('Linea');
      $table->string('DetItem')->nullable();
      $table->string('CpaOper')->nullable();
      $table->string('UniCodi')->nullable();
      $table->string('DetUnid')->nullable();
      $table->string('Detcodi')->nullable();
      $table->string('Detnomb')->nullable();
      $table->string('MarNomb')->nullable();
      $table->float('DetCant')->nullable();
      $table->string('DetPrec')->nullable();
      $table->float('DetDct1')->nullable();
      $table->float('DetDct2')->nullable();
      $table->float('DetImpo')->nullable();
      $table->float('DetPeso')->nullable();
      $table->string('DetEsta')->nullable();
      $table->string('DetEsPe')->nullable();
      $table->float('DetPerc')->nullable();
      $table->float('DetCSol')->nullable();
      $table->float('DetCDol')->nullable();
      $table->float('DetIgvv')->nullable();
      $table->float('DetCGia')->nullable();
      $table->string('OrdOper')->nullable();
      $table->string('CccCodi')->nullable();
      $table->string('GuiOper')->nullable();
      $table->string('Guiline')->nullable();
      $table->string('Detfact')->default('1');
      $table->float('DetSdCa')->nullable();
      $table->string('lote')->nullable();
      $table->date('detfven')->nullable();
      $table->primary(['Linea']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('compras_detalle');
  }
}
