<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasRaCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_ra_cab', function (Blueprint $table) {
            $table->string('EmpCodi');
            $table->string('PanAno');
            $table->string('PanPeri');
            $table->string('MesCodi');
            $table->string('TipoOper');
            $table->string('NumOper');
            $table->string('DocNume');
            $table->date('DocFechaE')->nullable();
            $table->date('DocFechaD')->nullable();
            $table->dateTime('DocFechaEv')->nullable();
            $table->string('DocDesc')->nullable();
            $table->string('DocMotivo')->nullable();
            $table->string('DocTicket')->nullable();
            $table->integer('DocXML')->nullable();
            $table->integer('DocPDF')->nullable();
            $table->integer('DocCDR')->nullable();
            $table->string('DocCHash')->nullable();
            $table->integer('DocCEsta')->nullable();
            $table->string('DocEstado')->nullable();
            $table->string('User_Crea')->nullable();
            $table->dateTime('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->dateTime('User_FModi')->nullable();
            $table->string('User_EModi')->nullable();
            $table->string('UDelete')->nullable();
            $table->string('MonCodi')->nullable();
            $table->string('LocCodi')->nullable();
            $table->float('icbper_unit')
            ->default(0)
            ->nullable();
            $table->float('icbper_value')
            ->default(0)
            ->nullable();            
            // $table->primary(['PanAno', 'EmpCodi', 'PanPeri', 'MesCodi', 'TipoOper', 'NumOper', 'DocNume'], 'primary_full' );
            $table->primary(['PanAno', 'EmpCodi', 'NumOper', 'DocNume' ], 'primary_full' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_ra_cab');
    }
}
