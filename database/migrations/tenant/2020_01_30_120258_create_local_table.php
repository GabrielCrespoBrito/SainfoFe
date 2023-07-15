<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('local', function (Blueprint $table) {
			$table->string('LocCodi');
			$table->string('LocNomb')->nullable();
			$table->string('LocDire')->nullable();
			$table->string('LocDist')->nullable();
			$table->string('LocTele')->nullable();
			$table->string('SerGuiaSal')->nullable();
			$table->string('NumGuiaSal')->nullable();
			$table->string('Numlibre')->nullable();
			$table->string('Numletra')->nullable();
			$table->string('SerLetra')->nullable();
      $table->integer('PDFLocalNombreInd')
      ->default(0)
      ->nullable();
			$table->date('Fecha')->nullable();
			$table->string('EmpCodi');
			$table->primary(['EmpCodi', 'LocCodi'], 'primary_full' );

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('local');
	}
}
