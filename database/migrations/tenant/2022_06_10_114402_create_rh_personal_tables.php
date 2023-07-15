<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhPersonalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rh_personal', function (Blueprint $table) {
          $table->bigIncrements('RHPCodi');
          $table->string('RHPNDoc')->nullable();
          $table->string('RHPNomb')->nullable();
          $table->string('RHPDire')->nullable();
          $table->string('RHPTele')->nullable();
          $table->string('RHPFNac')->nullable();
          $table->integer('RHPGen')->nullable();
          $table->integer('RHPECiv')->nullable();
          $table->integer('RHPGrad')->nullable();
          $table->integer('RHPEsta')->nullable();
          $table->string('RHPFoto')->nullable();
          $table->string('RHDCodi')->nullable();
          $table->float('RHPSueld')->nullable();
          $table->float('RHPHE')->nullable();
          $table->date('RHPFing')->nullable();
          $table->float('RHPOIng')->nullable();
          $table->float('RHPOEgr')->nullable();
          $table->float('RHDebe')->nullable();
          $table->float('RHHaber')->nullable();
          $table->integer('CarCodi')->nullable();
          $table->string('User_Crea')->nullable();
          $table->timestamp('User_FCrea')->nullable();
          $table->string('User_ECrea')->nullable();
          $table->string('User_Modi')->nullable();
          $table->timestamp('User_FModi')->nullable();
          $table->string('User_EModi')->nullable();
          $table->tinyInteger('UDelete')->nullable();
          $table->string('empcodi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rh_personal');
    }
}
