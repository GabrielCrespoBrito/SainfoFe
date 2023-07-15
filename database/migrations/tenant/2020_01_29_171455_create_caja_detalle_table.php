<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja_detalle', function (Blueprint $table) {
            $table->string('Id');
            $table->string('CueCodi');
            $table->string('MocNume')->nullable();
            $table->string('DocNume')->nullable();
            $table->string('CajNume')->nullable();
            $table->date('MocFech')->nullable();
            $table->date('MocFecV')->nullable();
            $table->string('TIPMOV')->nullable();
            $table->string('MocNomb')->nullable();
            $table->string('CtoCodi')->nullable();
            $table->string('MonCodi')->nullable();
            $table->float('CtaImpo')->nullable();
            $table->float('CtaDias')->nullable();
            $table->float('CANINGS')->nullable();
            $table->float('CANEGRS')->nullable();
            $table->float('SALSOLE')->nullable();
            $table->float('CtaSald')->nullable();
            $table->float('CANINGD')->nullable();
            $table->float('CANEGRD')->nullable();
            $table->float('SALDOLA')->nullable();
            $table->float('TIPCAMB', 8, 3)->nullable();
            $table->date('FECANUl')->nullable();
            $table->string('ANULADO')->nullable();
            $table->string('CANMOV')->nullable();
            $table->string('MOTIVO')->nullable();
            $table->string('AUTORIZA')->nullable();
            $table->string('OTRODOC')->nullable();
            $table->string('LocCodi')->nullable();
            $table->string('UsuCodi')->nullable();
            $table->string('EgrIng')->nullable();
            $table->string('PCCodi')->nullable();
            $table->string('TDocCodi')->nullable();
            $table->string('User_Crea')->nullable();
            $table->dateTime('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->dateTime('User_FModi')->nullable();
            $table->string('User_EModi')->nullable();
            $table->string('UDelete')->nullable();
            $table->string('CheOper')->nullable();
            $table->string('empcodi')->default("001");
            $table->primary(['Id', 'CueCodi','empcodi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caja_detalle');
    }
}
