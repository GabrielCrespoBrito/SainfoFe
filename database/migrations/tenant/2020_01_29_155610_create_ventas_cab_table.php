<?php

use App\ModuloMonitoreo\StatusCode\StatusCode;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasCabTable extends Migration
{
	public function up()
	{
		Schema::create('ventas_cab', function (Blueprint $table) {
			$table->char('VtaOper', 10);
      $table->string('VtaOperC')->nullable();
			$table->string('EmpCodi');
			$table->char('PanAno', 4);
			$table->char('PanPeri', 2);
			$table->char('TidCodi', 2);
			$table->char('VtaSeri', 4);
			$table->char('VtaNumee', 8);
			$table->string('VtaNume')->nullable();
      $table->string('VtaUni')->nullable()->unique();
			$table->date('VtaFvta')->nullable();
			$table->date('vtaFpag')->nullable();
			$table->date('VtaFVen')->nullable();
			$table->string('PCCodi')->nullable();
			$table->string('ConCodi')->nullable();
      $table->string('TpgCodi')
      ->default(null)
      ->nullable();
			$table->string('ZonCodi')->nullable();
			$table->string('MonCodi')->nullable();
			$table->string('Vencodi')->nullable();
			$table->string('DocRefe')->nullable();
			$table->string('GuiOper')->nullable();
			$table->string('VtaObse')->nullable();
			$table->float('VtaTcam',8,3)->nullable();
			$table->double('Vtacant',8,3)->nullable();
			$table->float('Vtabase')->nullable();
			$table->float('VtaIGVV')->nullable();
			$table->float('VtaDcto')->nullable();
			$table->float('VtaInaf')->nullable();
			$table->float('VtaExon')->nullable();
			$table->float('VtaGrat')->nullable();
			$table->float('VtaISC')->nullable();
			$table->float('VtaImpo')->nullable();
			$table->char('VtaEsta', 1)->nullable();
			$table->string('UsuCodi')->nullable();
			$table->char('MesCodi', 6)->nullable();
			$table->char('LocCodi', 3)->nullable();
			$table->float('VtaPago')->nullable();
			$table->string('VtaSald')->nullable();
      $table->float('VtaEfectivo')->nullable();
      $table->float('VtaVuelto')->nullable();
			$table->char('VtaEsPe', 2)->nullable();
			$table->float('VtaPPer')->nullable();
			$table->float('VtaAPer')->nullable();
			$table->float('VtaPerc')->nullable();
			$table->float('VtaTota')->nullable();
			$table->float('VtaSPer')->nullable();
			$table->string('TipCodi')->nullable();
			$table->string('AlmEsta')->nullable();
			$table->string('CajNume')->nullable();
			$table->float('VtaSdCa')->nullable();
			$table->string('VtaHora')->nullable();
			$table->string('vtafact')->nullable();
			$table->string('vtaanu')->nullable();
			$table->string('vtaadoc')->nullable();
			$table->string('VtaPedi')->nullable();
			$table->string('VtaEOpe')->nullable();
			$table->string('User_Crea')->nullable();
			$table->dateTime('User_FCrea')->nullable();
			$table->string('User_ECrea')->nullable();
			$table->string('User_Modi')->nullable();
			$table->dateTime('User_FModi')->nullable();
			$table->string('User_EModi')->nullable();
			$table->string('UDelete')->nullable();
			$table->dateTime('fe_fxml')->nullable();
			$table->dateTime('fe_fenvio')->nullable();
			$table->string('fe_estado')->nullable();
			$table->string('fe_obse')->nullable();
			$table->integer('fe_rpta')->nullable();
			$table->integer('fe_rptaa')->nullable();
			$table->string('fe_firma')->nullable();
			$table->char('VtaTDR', 2)->nullable();
			$table->char('VtaSeriR', 4)->nullable();
			$table->char('VtaNumeR', 8)->nullable();
			$table->date('VtaFVtaR')->nullable();
			$table->integer('VtaXML')->nullable();
			$table->integer('VtaPDF')->nullable();
			$table->integer('VtaCDR')->nullable();
			$table->integer('VtaMail')->nullable();
			$table->string('VtaFMail')
      ->nullable()
      ->default(StatusCode::CODE_0011);
			$table->string('Numoper')->nullable();
			$table->string('TipoOper')->nullable();
			$table->string('fe_version')->nullable();
			$table->string('VtaDetrPorc')->nullable();
			$table->string('VtaDetrTota')->nullable();
			$table->string('VtaTotalDetr')->nullable();
			$table->text('CuenCodi')->nullable();
			$table->string('VtaDetrCode')->default(0);
			$table->string('VtaAnticipo')->default(0);
			$table->string('VtaNumeAnticipo')->nullable();
			$table->string('VtaTidCodiAnticipo')->nullable();
			$table->string('VtaTotalAnticipo')->nullable();
			$table->integer('contingencia')
				->nullable()
				->default(0);
			$table->float('icbper')
				->nullable()
				->default(0);
			$table->primary(['VtaOper', 'EmpCodi', 'PanAno', 'PanPeri', 'TidCodi', 'VtaSeri'], 'primary_full');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ventas_cab');
	}
}
