<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_pago', function (Blueprint $table) {
            $table->string('PagOper');
            $table->string('VtaOper')->nullable();
            $table->string('TpgCodi')->nullable();
            $table->date('PagFech')->nullable();
            $table->float('PagTCam', 8, 3)->nullable();
            $table->string('MonCodi')->nullable();
            $table->float('PagImpo')->nullable();
            $table->string('BanCodi')->nullable();
            $table->string('Bannomb')->nullable();
            $table->string('VtaNume')->nullable();
            $table->date('VtaFVta')->nullable();
            $table->date('VtaFVen')->nullable();
            $table->string('PagBoch')->nullable();
            $table->date('usufech')->nullable();
            $table->string('usuhora')->nullable();
            $table->string('usucodi')->nullable();
            $table->string('CajNume')->nullable();
            $table->string('antnume')->nullable();
            $table->string('CtoOper')->nullable();
            $table->string('CheP')->nullable();
            $table->string('ChECodi')->nullable();
            $table->string('PCCodi')->nullable();
            $table->integer('VtaFact')->nullable();
            $table->string('EmpCodi');
            $table->string('PanAno')->nullable();
            $table->string('PanPeri')->nullable();
            $table->string('User_Crea')->nullable();
            $table->date('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->date('User_FModi')->nullable();
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
        Schema::dropIfExists('ventas_pago');
    }
}
