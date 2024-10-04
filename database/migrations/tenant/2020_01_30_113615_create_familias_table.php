<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamiliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->string('famCodi');
            $table->string('famNomb');
            $table->string('gruCodi');
            $table->string('famesta')->nullable();            
            $table->string('empcodi')->default("001");
            $table->primary(['famCodi', 'empcodi', 'gruCodi'], 'primary_full');
            $table->string('UDelete')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias');
    }
}
