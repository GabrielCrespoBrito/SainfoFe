<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCondicionCpraVtaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condicion_cpra_vta', function (Blueprint $table) {
            $table->string('CcvCodi');
            $table->longText('CcvDesc')->nullable();
            $table->string('empcodi');
            $table->primary(['CcvCodi', 'empcodi']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('condicion_cpra_vta');
    }
}
