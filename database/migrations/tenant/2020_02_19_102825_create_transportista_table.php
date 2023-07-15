<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportistaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('transportista', function (Blueprint $table) {
            $table->string('TraCodi');
            $table->string('Nombres');
            $table->string('Apellidos');
            $table->string('TDocCodi');
            $table->string('TraDire')->nullable();
            $table->string('TraTele')->nullable();
            $table->string('TraRucc')->nullable();
            $table->string('TraLice')->nullable();
            $table->string('EmpCodi')->nullable();
            $table->primary('TraCodi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transportista');
    }
}
