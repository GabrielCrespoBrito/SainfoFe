<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->string('Vencodi');
            $table->string('vennomb')->nullable();
            $table->string('vendire')->nullable();
            $table->string('ventel1')->nullable();
            $table->string('ventel2')->nullable();
            $table->string('vensuel')->nullable();
            $table->float('vencomi')->nullable();
            $table->float('venneto')->nullable();
            $table->string('venmail')->nullable();
            $table->string('usucodi')->nullable();
            $table->string('empcodi');
            $table->string('defecto')->nullable();
            $table->primary(['empcodi', 'Vencodi'], 'primary_full');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendedores');
    }
}