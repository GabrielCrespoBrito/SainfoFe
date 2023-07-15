<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTcmonedaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tcmoneda', function (Blueprint $table) {
            $table->string('TipCodi');
            $table->string('TipFech');
            $table->float('TipComp')->nullable();
            $table->float('TipVent')->nullable();
            $table->string('empcodi');
            $table->primary(['empcodi', 'TipCodi', 'TipFech'], 'primary_full');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tcmoneda');
    }
}
