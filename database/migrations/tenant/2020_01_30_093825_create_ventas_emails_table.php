<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_emails', function (Blueprint $table) {
            $table->string('EmpCodi');
            $table->string('VtaOper')->nullable();
            $table->string('DetItem')->nullable();
            $table->dateTime('DetFecha')->nullable();
            $table->string('UsuCodi')->nullable();
            $table->string('DetEmail')->nullable();
            $table->string('DetEsta')->nullable();
            $table->dateTime('DetFechaD')->nullable();
            $table->string('DetAsun')->nullable();
            $table->string('DetMens')->nullable();
            $table->integer('DetPDF')->nullable();
            $table->integer('DetXML')->nullable();
            $table->integer('DetCDR')->nullable();
            $table->primary(['VtaOper', 'EmpCodi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_emails');
    }
}
