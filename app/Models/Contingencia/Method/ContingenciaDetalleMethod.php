<?php 

namespace App\Models\Contingencia\Method;

use App\Venta;
use App\ModuloMonitoreo\StatusCode\StatusCode;

trait ContingenciaDetalleMethod 
{
	public static function createFromDocument( $vtaoper , $motivo , $contingencia )
	{
		$documento = Venta::find($vtaoper);

		$data = [
			'empcodi' => $contingencia->empcodi,
			'con_id' => $contingencia->id,
			'vtaoper' => $vtaoper,
			'tidcodi' => $documento->TidCodi,
			'serie' => $documento->VtaSeri,
			'numero' => $documento->VtaNumee,
			'motivo_id' => $motivo,
			'gravada' => $documento->Vtabase,
			'exonerada' => $documento->VtaExon,
			'inafecta' => $documento->VtaInaf,
			'igv' => $documento->VtaIGVV,
			'isc' => $documento->VtaISC,
			'total' => $documento->VtaTota,
			'tidcodi_ref' => $documento->VtaTDR,
			'serie_ref' => $documento->VtaSeriR,
			'numero_ref' => $documento->VtaFVtaR,
		];

		$detalle = self::create($data);
		$documento->confirmResumen();
	
	}

}