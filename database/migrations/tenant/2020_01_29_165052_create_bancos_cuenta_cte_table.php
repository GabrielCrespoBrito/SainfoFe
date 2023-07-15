<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBancosCuentaCteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos_cuenta_cte', function (Blueprint $table) {
            $table->string('BanCodi');
            $table->string('CueCodi');
            $table->string('CueNume')->nullable();
            $table->string('CueSald')->nullable();
            $table->string('CueImSd')->nullable();
            $table->string('CueImSC')->nullable();
            $table->string('MonCodi')->nullable();
            $table->string('CueSect')->nullable();
            $table->string('CueObse')->nullable();
            $table->string('User_Crea')->nullable();
            $table->string('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->string('User_FModi')->nullable();
            $table->string('User_EModi')->nullable();
            $table->string('UDelete')->nullable();
            $table->string('CueCorrE')->nullable();
            $table->string('CueCorrD')->nullable();
            $table->string('test')->default(1);
            $table->string('EmpCodi')->default("001");
            $table->string('Detract')->default(0);
            $table->primary(['BanCodi', 'CueNume', 'EmpCodi']);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bancos_cuenta_cte');
    }
}
