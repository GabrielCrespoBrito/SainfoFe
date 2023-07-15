<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiasVentasTable extends Migration
{
  /**
   * @CRONOGRAMA DE HOY
   * 
   * - METAS DE LA SEMANA (TMI)
   * - META DE INGLES
   * 
   * 
   */


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guias_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('GuiOper');
            $table->string('VtaOper');
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
        Schema::dropIfExists('guias_ventas');
    }
}
