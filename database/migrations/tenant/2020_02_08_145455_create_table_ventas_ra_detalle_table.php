<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVentasRaDetalleTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ventas_ra_detalle', function (Blueprint $table) {
			$table->integer('id')->nullable();
			$table->string('EmpCodi');
			$table->string('PanAno');
			$table->string('PanPeri');
			$table->string('numoper');
			$table->string('docNume');
			$table->string('DetItem');
			$table->date('detfecha')->nullable();
			$table->string('tidcodi')->nullable();
			$table->string('detseri')->nullable();
			$table->string('detNume')->nullable();
			$table->string('DetNume1')->nullable();
			$table->string('DetMotivo')->nullable();
			$table->float('DetGrav')->nullable();
			$table->float('DetExon')->nullable();
			$table->float('DetInaf')->nullable();
			$table->float('DetIGV')->nullable();
			$table->float('DetISC')->nullable();
			$table->float('DetTota')->nullable();
			$table->string('PCCodi')->nullable();
			$table->string('PCRucc')->nullable();
			$table->string('TDocCodi')->nullable();
			$table->string('VtaEsta')->nullable();
			$table->string('vtatdr')->nullable();
			$table->string('vtaserir')->nullable();
			$table->string('vtanumer')->nullable();
			$table->float('icbper_unit')
			->default(0)
			->nullable();
			$table->float('icbper_value')
			->default(0)
			->nullable();

			$table->primary(['EmpCodi', 'numoper', 'docNume', 'DetItem'], 'full_primary_key');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ventas_ra_detalle');
	}
}
