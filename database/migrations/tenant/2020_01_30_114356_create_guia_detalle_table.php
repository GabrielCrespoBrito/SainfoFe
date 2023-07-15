<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiaDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_detalle', function (Blueprint $table) {
            $table->string('DetItem')->nullable();
            $table->string('GuiOper')->nullable();
            $table->string('Linea')->nullable();
            $table->string('UniCodi')->nullable();
            $table->string('DetNomb')->nullable();
            $table->string('MarNomb')->nullable();
            $table->float('Detcant')->nullable();
            $table->string('DetPrec')->nullable();
            $table->string('DetDct1')->nullable();
            $table->float('DetDct2')->nullable();
            $table->double('DetImpo')->nullable();
            $table->float('DetPeso')->nullable();
            $table->string('DetUnid')->nullable();
            $table->string('DetCodi')->nullable();
            $table->float('DetCSol')->nullable();
            $table->float('DetCDol')->nullable();
            $table->float('CCoCodi')->nullable();
            $table->float('CpaVtaCant')->nullable();
            $table->string('CpaVtaOpe')->nullable();
            $table->string('CpaVtaLine')->nullable();
            $table->string('DetTipo')->nullable();
            $table->float('DetEsPe')->nullable();
            $table->string('DetFact')
            ->default(1)
            ->nullable();
            $table->string('TidCodi')->nullable();
            $table->float('DetDcto')->nullable();
            $table->float('DetIng')->nullable();
            $table->float('DetSal')->nullable();
            $table->string('DetDeta')->nullable();
            $table->string('lote')->nullable();
            $table->date('detfven')->nullable();
            $table->string('empcodi')->nullable();
            $table->primary(['Linea'], 'primary_full');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guia_detalle');
    }
}
