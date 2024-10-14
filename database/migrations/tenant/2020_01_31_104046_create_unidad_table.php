<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('unidad', function (Blueprint $table) {
			$table->increments('Unicodi');
			$table->string('Id')->nullable();
			$table->integer('UniEnte')->nullable();
			$table->integer('UniMedi')->nullable();
			$table->string('UniAbre')->nullable();
			$table->string('UniPUCD')->nullable();
			$table->string('UniPUCS')->nullable();
			$table->string('UniMarg')->nullable();
			$table->string('UNIPUVD')->nullable();
			$table->string('UNIPUVS')->nullable();
			$table->string('UniPeso')->nullable();
			$table->float('UniPAdi')->nullable();
			$table->float('UniMarg1')->nullable();
			$table->float('UniPUVS1')->nullable();
      $table->float('porc_com_vend')->nullable();
      $table->string('UniPMVS')
      ->nullable()
      ->default(0);
      $table->string('UniPMVD')
      ->nullable()
      ->default(0);
			$table->string('empcodi');
			$table->string('UniPUVD1')->nullable();
			$table->string('LisCodi')->nullable();
      
      $table->string('User_Crea')->nullable();
      $table->dateTime('User_FCrea')->nullable();
      $table->string('User_ECrea')->nullable();
      $table->string('User_Modi')->nullable();
      $table->dateTime('User_FModi')->nullable();
      $table->string('User_EModi')->nullable();

			// $table->primary(['empcodi', 'Unicodi'], 'primary_full');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('unidad');
	}
}
