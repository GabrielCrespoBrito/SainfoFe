<?php

namespace App\Http\Controllers\TipoCambio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipoCambioUpdatedTodayRequest;
use App\SettingSystem;
use App\TipoCambioMoneda;
use App\Util\ConsultTipoCambio\ConsultTipoCambioInterface;

class TipoCambioController extends Controller
{
	public $tcConsulter;

	public function __construct( ConsultTipoCambioInterface $tcConsulter )
	{
		$this->tcConsulter = $tcConsulter;
	}


	public function updatedToday(TipoCambioUpdatedTodayRequest $request )
	{
		$tc = TipoCambioMoneda::updateOrCreate(['TipFech' => date('Y-m-d')] , [
			'TipFech' => date('Y-m-d'),
			'TipComp' => $request->TipComp,
			'TipVent' => $request->TipVent,			
		]);


		return response()->json(['message' => 'Actualización de tipo de cambio realizada'], 200 );
	}


	public function currentTC( Request $request )
	{
		$tc = TipoCambioMoneda::lastTC();
		$tcVenta = optional($tc)->venta ?? 0;
		$tcCompra = optional($tc)->compra ?? 0;
		
		$tcSunat = SettingSystem::getCurrentTCSunat();

		$ultimaTcExtraido = $tcSunat['fecha'];
		$tcSunatVenta = $tcSunat['venta'];
		$tcSunatCompra = $tcSunat['compra'];

		$html = view('tipo_cambio.form_current', compact('ultimaTcExtraido', 'tcVenta', 'tcCompra', 'tcSunatVenta', 'tcSunatCompra'));
		return $html;
	}
}

