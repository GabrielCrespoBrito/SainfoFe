<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCondicionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condicion', function (Blueprint $table) {
            $table->string('conCodi')->nullable();
            $table->string('connomb')->nullable();
            $table->string('condias')->nullable();
            $table->string('contipo')->nullable();
            $table->string('empcodi')->default("001");
            $table->integer('system')->default(0);
            $table->primary(['conCodi', 'empcodi']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('condicion');
    }
}
