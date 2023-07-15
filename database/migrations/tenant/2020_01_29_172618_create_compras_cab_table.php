<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasCabTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('compras_cab', function (Blueprint $table) {
			$table->string('CpaOper');
			$table->string('EmpCodi')->nullable();
			$table->string('PanAno')->nullable();
			$table->string('PanPeri')->nullable();
			$table->string('CpaSerie')->nullable();
			$table->string('CpaNumee')->nullable();
			$table->string('CpaNume')->nullable();
			$table->date('CpaFCpa')->nullable();
			$table->date('CpaFCon')->nullable();
			$table->date('CpaFPag')->nullable();
			$table->date('CpaFven')->nullable();
			$table->string('PCcodi')->nullable();
			$table->string('TidCodi')->nullable();
			$table->string('concodi')->nullable();
      $table->string('TpgCodi')
      ->default(null)
      ->nullable();
			$table->string('zoncodi')->nullable();
			$table->string('moncodi')->nullable();
			$table->string('vencodi')->nullable();
			$table->string('Docrefe')->nullable();
			$table->string('GuiOper')->nullable();
			$table->string('Cpaobse')->nullable();
			$table->float('CpaTCam',8,3)->nullable();
			$table->float('CpaSdCa')->nullable();
			$table->float('CpaTPes')->nullable();
			$table->float('Cpabase')->nullable();
			$table->float('CpaIGVV')->nullable();
			$table->float('CpaImpo')->nullable();
			$table->string('CpaEsta')->nullable();
			$table->string('usuCodi')->nullable();
			$table->string('MesCodi')->nullable();
			$table->string('LocCodi')->nullable();
			$table->float('CpaPago')->nullable();
			$table->float('CpaSald')->nullable();
			$table->char('CpaEsPe',2)->nullable();
			$table->float('CpaPPer')->nullable();
			$table->float('CpaAPer')->nullable();
			$table->float('CpaPerc')->nullable();
			$table->string('Cpatota')->nullable();
			$table->string('TipCodi')->nullable();
			$table->string('AlmEsta')->nullable();
			$table->string('CajNume')->nullable();
			$table->float('AjuNeto')->nullable();
			$table->float('AjuIGVV')->nullable();
			$table->string('IGVEsta')->nullable();
			$table->float('IGVImpo')->nullable();
			$table->string('CpaEstado')->nullable();
			$table->string('CpaEOpe')->nullable();
			$table->string('User_Crea')->nullable();
			$table->dateTime('User_FCrea')->nullable();
			$table->string('User_ECrea')->nullable();
			$table->string('User_Modi')->nullable();
			$table->dateTime('User_FModi')->nullable();
			$table->string('User_EModi')->nullable();
			$table->char('UDelete', 1)->nullable();
      $table->primary(['CpaOper', 'EmpCodi']);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('compras_cab');
	}
}
