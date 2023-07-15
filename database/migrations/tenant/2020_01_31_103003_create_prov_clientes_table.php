<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvClientesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prov_clientes', function (Blueprint $table) {
			$table->string('EmpCodi');
			$table->string('PCCodi');
			$table->string('TipCodi');
			$table->string('PCNomb')->nullable();
			$table->string('PCRucc')->nullable();
			$table->string('PCDire',255)->nullable();
			$table->string('PCDist')->nullable();
			$table->string('PCTel1')->nullable();
			$table->string('PCTel2')->nullable();
			$table->string('PCMail')->nullable();
			$table->string('PCCont')->nullable();
			$table->string('PCCMail')->nullable();
			$table->string('VenCodi')->nullable();
			$table->string('ZonCodi')->nullable();
			$table->string('TdoCodi')->nullable();
			$table->string('PCDocu')->nullable();
			$table->string('MonCodi')->nullable();
			$table->float('PCAfPe')->nullable();
			$table->integer('LisCodi')->nullable();
			$table->float('PCLine')->nullable();
			$table->integer('PCAfli')->nullable();
			$table->float('PCDeud')->nullable();
			$table->string('PCANom')->nullable();
			$table->string('PCARuc')->nullable();
			$table->string('PCADir')->nullable();
			$table->string('PCATel')->nullable();
			$table->string('PCAEma')->nullable();
			$table->string('TDocCodi')->nullable();
			$table->string('PConCodi')->nullable();
			$table->binary('PCFoto')->nullable();
			$table->string('User_Crea')->nullable();
			$table->dateTime('User_FCrea')->nullable();
			$table->string('User_ECrea')->nullable();
			$table->string('User_Modi')->nullable();
			$table->dateTime('User_FModi')->nullable();
			$table->string('User_EModi')->nullable();
			$table->char('UDelete', 1)->nullable(0);
			$table->primary(['EmpCodi', 'PCCodi', 'TipCodi'], 'primary_full');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('prov_clientes');
	}
}
