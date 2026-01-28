<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->string('EmpCodi');
            $table->string('LocCodi');
            $table->string('CotNume');
            $table->string('CotUni')->nullable()->unique();
            $table->date('CotFVta')->nullable();
            $table->date('CotFVen')->nullable();
            $table->string('PcCodi')->nullable();
            $table->string('ConCodi')->nullable();
            $table->string('zoncodi')->nullable();
            $table->string('moncodi')->nullable();
            $table->string('vencodi')->nullable();
            $table->string('Docrefe')->nullable();
            $table->string('VtaOper')->nullable();
            $table->string('cotobse')->nullable();
            $table->float('CotTCam',8,3)->nullable();
            $table->float('cotcant')->nullable();
            $table->float('cotbase')->nullable();
            $table->float('cotigvv')->nullable();
            $table->float('cotisc')->nullable();
            $table->float('cotdcto')->nullable();            
            $table->float('cotimpo')->nullable();
            $table->string('cotesta')->nullable();
            $table->string('usucodi')->nullable();
            $table->string('mescodi')->nullable();
            $table->longText('CotCond')->nullable();
            $table->string('Cotcont')->nullable();
            $table->string('CotEsPe')->nullable();
            $table->float('CotPPer')->nullable();
            $table->float('CotAPer')->nullable();
            $table->float('CotPerc')->nullable();
            $table->float('icbper')
            ->nullable()
            ->default(0);
            $table->string('CotTota')->nullable();
            $table->string('TipCodi')->nullable();
            $table->string('TidCodi')->nullable();
            $table->string('TidCodi1');
            $table->time('hora')->nullable();
            $table->primary(['LocCodi', 'EmpCodi', 'CotNume'], 'primary_full' );


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizaciones');
    }
}
