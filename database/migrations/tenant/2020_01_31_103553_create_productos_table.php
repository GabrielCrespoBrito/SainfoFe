<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('ProCodi')->unique();
            $table->string('famcodi')->nullable();
            $table->string('grucodi')->nullable();
            $table->string('marcodi')->nullable();
            $table->string('ProCodi1')->unique()->nullable();
            $table->string('ProNomb')->nullable();
            $table->integer('proesta')->default(1);
            $table->float('prosto1')->nullable();
            $table->float('prosto2')->nullable();
            $table->float('prosto3')->nullable();
            $table->float('prosto4')->nullable();
            $table->float('prosto5')->nullable();
            $table->float('prosto6')->nullable();
            $table->float('prosto7')->nullable();
            $table->float('prosto8')->nullable();
            $table->float('prosto9')->nullable();
            $table->float('prosto10')->nullable();
            $table->string('unpcodi')->nullable();
            $table->string('tiecodi')->nullable();
            $table->string('moncodi')->nullable();
            $table->float('provaco')->nullable();
            $table->float('proigco')->nullable();
            $table->string('ProPUCD')->nullable();
            $table->string('ProPUCS')->nullable();
            $table->string('ProMarg')->nullable();
            $table->string('ProPUVD')->nullable();
            $table->string('ProPUVS')->nullable();
            $table->string('ProPMVS')
              ->nullable()
              ->default(0);
              $table->string('ProPMVD')
              ->nullable()
              ->default(0);
            $table->float('ProMarg1')->nullable();
            $table->float('ProPUVS1')->nullable();
            $table->float('ProPUVD1')->nullable();
            $table->float('ProPeso')->nullable();
            $table->string('ProPerc')->nullable();
            $table->float('Promini')->nullable();
            $table->string('proubic')->nullable();
            $table->string('ProUltC')->nullable();
            $table->string('ProUltF')->nullable();
            $table->float('proproms')->nullable();
            $table->float('propromd')->nullable();
            $table->float('proigvv')->nullable();
            $table->float('ProPUCL')->nullable();
            $table->float('ProDcto1')->nullable();
            $table->float('Prodcto2')->nullable();
            $table->float('prodcto3')->nullable();
            $table->float('ProIgv1')->nullable();
            $table->float('ProPerc1')->nullable();
            $table->string('Proobse')->nullable();
            $table->string('proingre')->nullable();
            $table->string('prouso')->nullable();
            $table->string('ctacpra')->nullable();
            $table->string('ctavta')->nullable();
            $table->string('profoto')->nullable();
            $table->string('ProSTem')->nullable();
            $table->string('ProcCodi')->nullable();
            $table->string('User_Crea')->nullable();
            $table->dateTime('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->dateTime('User_FModi')->nullable();
            $table->string('User_EModi')->nullable();
            $table->string('UDelete')->default(0);
            $table->string('BaseIGV')->nullable();
            $table->float('ISC')->nullable();
            $table->float('porc_com_vend')->nullable();
            $table->boolean('icbper')
            ->nullable()
            ->default(0);
            $table->boolean('incluye_igv')
            ->nullable()
            ->default(1);                       
            $table->string('empcodi');
            $table->string('profoto2')->nullable();            
            // $table->primary(['empcodi', 'ID', 'ProCodi'], 'primary_full');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
