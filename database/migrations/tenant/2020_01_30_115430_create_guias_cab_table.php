<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guias_cab', function (Blueprint $table) {
            $table->string('GuiOper');
            $table->string('EmpCodi');
            $table->string('PanAno');
            $table->string('PanPeri');
            $table->string('EntSal')->nullable();
            $table->string('TidCodi1')
            ->default('09')
            ->nullable();
            $table->string('GuiSeri')->nullable();
            $table->string('GuiNumee')->nullable();
            $table->string('GuiNume')->nullable();
            $table->string('GuiUni')->nullable()->unique();
            $table->date('GuiFemi')->nullable();
            $table->date('GuiFDes')->nullable();
            $table->string('TmoCodi')->nullable();
            $table->string('GuiEsta')->nullable();
            $table->string('PCCodi')->nullable();
            $table->string('DCodi')->nullable();
            $table->string('zoncodi')->nullable();
            $table->string('vencodi')->nullable();
            $table->string('Loccodi')->nullable();
            $table->string('moncodi')->nullable();
            $table->float('guiTcam',8,3)->nullable();
            $table->string('tracodi')->nullable();
            $table->string('guiobse')->nullable();
            $table->string('guipedi')->nullable();
            $table->float('guicant')->nullable();
            $table->float('guitbas')->nullable();
            $table->float('guiporp')->nullable();
            $table->string('GuiEsPe')->nullable();
            $table->string('docrefe')->nullable();
            $table->string('guidirp')->nullable();
            $table->string('guidisp')->nullable();
            $table->string('guidill')->nullable();
            $table->string('guidisll')->nullable();
            $table->string('motcodi')->nullable();
            $table->string('VehCodi')->nullable();
            $table->string('concodi')->nullable();
            $table->string('mescodi')->nullable();
            $table->string('usucodi')->nullable();
            $table->string('TipCodi')->nullable();
            $table->string('cpaOper')->nullable();
            $table->string('vtaoper')->nullable();
            $table->string('TidCodi')->nullable();
            $table->integer('IGVEsta')->nullable();
            $table->string('GuiNOpe')->nullable();
            $table->string('CtoOper')->nullable();
            $table->string('TraOper')->nullable();
            $table->string('GuiEFor')->nullable();
            $table->string('GuiEOpe')->nullable();
            $table->string('TippCodi')->nullable();
            $table->string('User_Crea')->nullable();
            $table->dateTime('User_FCrea')->nullable();
            $table->string('User_ECrea')->nullable();
            $table->string('User_Modi')->nullable();
            $table->dateTime('User_FModi')->nullable();
            $table->string('User_EModi')->nullable();
            $table->string('UDelete')->nullable();
            $table->integer('GuiXML')->default(0);
            $table->integer('GuiPDF')->default(0);
            $table->integer('GuiCDR')->default(0);
            $table->integer('GuiMail')->default(0);
            $table->integer('fe_rpta')->default(9);
            $table->string('fe_ticket')->nullable();
            $table->string('fe_ticket_frecepcion')->nullable();
            $table->longText('fe_rpta_api')->nullable();
            $table->string('fe_obse')->nullable();
            $table->string('fe_firma')->nullable();
            $table->string('e_traslado')->nullable();
            $table->string('e_conformidad')->nullable();
            $table->string('obs_traslado')->nullable();
            $table->string('mod_traslado')->nullable();            
            $table->primary(['GuiOper', 'EmpCodi', 'PanAno', 'PanPeri'], 'primary_full');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guias_cab');
    }
}
