<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosPendientesDetalleTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documentos_pendientes_detalle', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('id_documento_pendiente');
			$table->string('VtaOper');
			$table->string('EmpCodi');
			$table->string('VtaNume');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('documentos_pendientes_detalle');
	}
}
