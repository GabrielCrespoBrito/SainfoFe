<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosDetMatInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_det_mat_inventario', function (Blueprint $table) {
            $table->increments('Item');
            $table->unsignedInteger('InvCodi');
            $table->foreign('InvCodi')
            ->references('InvCodi')
            ->on('productos_mat_inventario')
            ->onDelete('cascade');
            $table->string('Id')->nullable();
            $table->string('ProCodi')->nullable();
            $table->string('ProMarc')->nullable();
            $table->string('proNomb')->nullable();
            $table->string('UnpCodi')->nullable();
            $table->float('ProStock')->default(1);
            $table->float('ProInve')->nullable();
            $table->float('ProPUCS')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_det_mat_inventario');
    }
}
