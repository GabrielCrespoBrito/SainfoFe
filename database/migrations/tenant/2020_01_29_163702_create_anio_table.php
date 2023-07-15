<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anio', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->string('empcodi');
            $table->string('Pan_cAnio');
            $table->string('Pan_cEstado')->nullable();
            $table->string('Pan_cUserCrea')->nullable();
            $table->dateTime('Pan_dFechaCrea')->nullable();
            $table->string('Pan_cUserModifica')->nullable();
            $table->dateTime('Pan_dFechaModifica')->nullable();
            $table->string('Pan_cEquipoUser')->nullable();
            $table->string('Per_cPeriodo')->nullable();
            $table->primary(['empcodi', 'Pan_cAnio']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anio');
    }
}
