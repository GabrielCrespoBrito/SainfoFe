<?php

use App\Moneda;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasCreditos extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ventas_creditos', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('item');
      $table->string('VtaOper');
      $table->string('monto');
      $table->date('fecha_pago');
      $table->string('forma_pago_id');
      $table->string('MonCodi')->default(Moneda::SOL_ID);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('ventas_creditos');
  }

  // --- INTER ---
}
