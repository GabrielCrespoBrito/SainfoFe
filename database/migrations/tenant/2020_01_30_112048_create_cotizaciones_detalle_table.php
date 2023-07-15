<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionesDetalleTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('cotizaciones_detalle', function (Blueprint $table) {
      $table->string('DetItem');
      $table->string('CotNume');
      $table->string('UniCodi')->nullable();
      $table->string('DetUnid')->nullable();
      $table->string('DetCodi')->nullable();
      $table->string('DetNomb')->nullable();
      $table->string('MarNomb')->nullable();
      $table->float('DetCant')->nullable();
      $table->string('DetFact')->default("1")->nullable();
      $table->string('DetPrec')->nullable();
      $table->float('DetImpo')->nullable();
      $table->float('DetPeso')->nullable();
      $table->string('DetEsta')->nullable();
      $table->string('DetEsPe')->nullable();
      $table->string('VtaOper')->nullable();
      $table->string('Vtacant')->nullable();
      $table->float('DetIGVV')->nullable();
      $table->float('DetCSol')->nullable();
      $table->float('DetCDol')->nullable();
      $table->float('DetDcto')->nullable();
      $table->float('DetDctoV')->default('0');
      $table->string('DetDeta')->nullable();
      $table->string('DetBase')->nullable();
      $table->float('DetISC')->nullable();
      $table->float('cotdcto')->default(0);
      $table->float('DetISCP')->nullable();
      $table->float('DetIGVP')->nullable();
      $table->float('DetPercP')->nullable();
      $table->boolean('incluye_igv')->default(1);
      $table->float('icbper_unit')
        ->default(0)
        ->nullable();
      $table->float('icbper_value')
        ->default(0)
        ->nullable();
      $table->string('EmpCodi');
      $table->primary(['DetItem', 'EmpCodi', 'CotNume'], 'primary_full');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('cotizaciones_detalle');
  }
}
