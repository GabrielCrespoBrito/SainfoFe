<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTransporteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_transporte', function (Blueprint $table) {
            $table->string('EmpCodi'); 
            $table->string('EmpNomb')->nullable(); 
            $table->string('EmpRucc')->nullable();
            $table->string('mtc')->nullable(); 
            $table->string('empresa_id')->default("001");
            $table->primary(['EmpCodi'], 'primary_full' );

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa_transporte');
    }
}
