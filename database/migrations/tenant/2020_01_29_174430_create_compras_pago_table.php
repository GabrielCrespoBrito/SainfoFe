<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_pago', function (Blueprint $table) {
            $table->string('PagOper');
            $table->string('CpaOper')->nullable();
            $table->string('TpgCodi')->nullable();
            $table->date('PagFech')->nullable();
			      $table->float('PagTCam', 8, 3)->nullable();
            $table->string('MonCodi')->nullable();
            $table->float('PagImpo')->nullable();
            $table->string('BanCodi')->nullable();
            $table->string('Bannomb')->nullable();
            $table->string('CpaNume')->nullable();
            $table->date('CpaFcpa')->nullable();
            $table->date('CpaFVen')->nullable();
            $table->string('PagBoch')->nullable();
            $table->date('usufech')->nullable();
            $table->string('usuhora')->nullable();
            $table->string('usucodi')->nullable();
            $table->string('cajnume')->nullable();
            $table->binary('cpafoto')->nullable();
            $table->string('CpaNCre')->nullable();
            $table->string('ChePT')->nullable();
            $table->string('EmpCodi')->nullable();
            $table->string('PanAno')->nullable();
            $table->string('PanPeri')->nullable();
            $table->string('User_Crea')->nullable();
            $table->dateTime('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->dateTime('User_FModi')->nullable();
            $table->string('User_EModi')->nullable();
            $table->string('UDelete')->nullable();
            $table->primary(['PagOper', 'EmpCodi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_pago');
    }
}
