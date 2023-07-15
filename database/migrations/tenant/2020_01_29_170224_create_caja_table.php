<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->string('CajNume')->nullable();
            $table->string('CueCodi')->nullable();
            $table->date('CajFech')->nullable();
            $table->double('CajSalS',16,2)->nullable();
            $table->double('CajSalD',16,2)->nullable();
            $table->string('CajEsta')->nullable();
            $table->string('UsuCodi')->nullable();
            $table->date('CajFecC')->nullable();
            $table->time('CajHora')->nullable();
            $table->string('LocCodi')->nullable();
            $table->string('EmpCodi')->nullable();
            $table->string('PanAno')->nullable();
            $table->string('PanPeri')->nullable();
            $table->string('MesCodi')->nullable();
            $table->string('User_Crea')->nullable();
            $table->dateTime('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->dateTime('User_FModi')->nullable();
            $table->string('User_EModi')->nullable();
            $table->char('UDelete', 1)->nullable();
            $table->primary(['CajNume', 'LocCodi', 'EmpCodi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caja');
    }
}
