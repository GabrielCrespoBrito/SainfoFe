<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->char(   'GruCodi', 3 )->unique();
            $table->string( 'GruNomb' );
            $table->string( 'GruEsta' )->nullable();
            $table->string( 'empcodi' );
            $table->primary([ 'GruCodi' , 'empcodi' ], 'primary_full');
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
        Schema::dropIfExists('grupos');
    }
}
