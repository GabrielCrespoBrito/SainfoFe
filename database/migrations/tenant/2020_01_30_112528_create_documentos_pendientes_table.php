<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosPendientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_pendientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('EmpCodi');
            $table->enum('tipo_documento', ['factura','boleta']);
            $table->string('cantidad');
            $table->string('lapso');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos_pendientes');
    }
}
