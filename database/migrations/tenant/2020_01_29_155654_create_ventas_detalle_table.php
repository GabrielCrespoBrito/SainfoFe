<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasDetalleTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ventas_detalle', function (Blueprint $table) {
			$table->char('Linea', 8)->nullable();
			$table->char('DetItem', 3)->nullable();
			$table->char('VtaOper', 10)->nullable();
			$table->string('UniCodi', 10)->nullable();
			$table->string('DetUnid', 4)->nullable();
			$table->string('DetCodi', 20)->nullable();
			$table->string('DetNomb')->nullable();
			$table->string('MarNomb', 20)->nullable();
			$table->float('DetCant')->nullable();
			$table->string('DetPrec')->nullable();
			$table->float('DetImpo')->nullable();
			$table->float('DetPeso')->nullable();
			$table->string('DetEsta', 1)->nullable();
			$table->float('DetEsPe')->nullable();
			$table->float('DetCSol')->default(0)->nullable();
      $table->float('DetCDol')->default(0)->nullable();      
      $table->float('DetVSol')->default(0)->nullable();
			$table->float('DetVDol')->default(0)->nullable();

      $table->float('DetPorcVend')->default(0)->nullable();
      $table->float('DetPorcVenSol')->default(0)->nullable();
      $table->float('DetPorcVenDol')->default(0)->nullable();
      
      $table->string('CCCCodi', 3)->nullable();
			$table->string('CotNume', 12)->nullable();
			$table->string('GuiOper', 10)->nullable();
			$table->string('GuiLine', 10)->nullable();
			$table->float('DetIGVV')->default('18');			
			$table->float('DetSdCa')->default('0');
			$table->string('Detfact')->default('1');
			$table->float('DetDcto')->default('0');
			$table->double('DetDctoV',8,2)->default('0');
			$table->string('DetDeta', 504)->nullable();
			$table->string('Estado', 12)->nullable();
			$table->text('lote')->nullable();
			$table->string('detfven', 10)->nullable();
			$table->string('DetBase')->nullable();
			$table->double('DetISC',8,2)->nullable();
			$table->double('DetISCP',8,2)->nullable();
			$table->double('DetIGVP',8,2)->nullable();
			$table->double('DetPercP',8,2)->nullable();
			$table->float('icbper_unit')
			->default(0)
			->nullable();
			$table->float('icbper_value')
			->default(0)
			->nullable();
			$table->boolean('incluye_igv')
			->nullable()
			->default(1);
			$table->string('EmpCodi')->default("001");
			$table->string('TipoIGV')->nullable();
			$table->primary(['Linea', 'EmpCodi']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ventas_detalle');
	}
}
