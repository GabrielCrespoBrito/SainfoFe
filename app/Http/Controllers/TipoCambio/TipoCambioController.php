<?php

namespace App\Http\Controllers\TipoCambio;

use App\SettingSystem;
use App\TipoCambioMoneda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\TipoCambioUpdatedTodayRequest;

class TipoCambioController extends Controller
{

	public function updatedToday(TipoCambioUpdatedTodayRequest $request )
	{
		$tc = TipoCambioMoneda::updateOrCreate(['TipFech' => date('Y-m-d')] , [
			'TipFech' => date('Y-m-d'),
			'TipComp' => $request->TipComp,
			'TipVent' => $request->TipVent,			
		]);

    if ($tc->isDirty()) {
      $tc->save();
      Cache::forget('ultimo_tc');
    }


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

