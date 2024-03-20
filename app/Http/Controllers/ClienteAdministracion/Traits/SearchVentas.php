<?php 

namespace App\Http\Controllers\ClienteAdministracion\Traits;

use App\Venta;
use App\Empresa;

trait SearchVentas
{

	public function searchByFilter( $empcodi , $fecha_desde , $fecha_hasta, $tipo = "todos", $estado = "todos" )
	{
		$busqueda = Venta::query()
		->with(['cliente_with' => function($q){
      $q->where('EmpCodi', empcodi())
      ->where('TipCodi', 'C'); } ,'moneda'])		
		->where('EmpCodi', $empcodi)
		->orderBy('ventas_cab.TidCodi', 'asc')
		->orderBy('ventas_cab.VtaNume', 'asc')
		->orderBy('ventas_cab.VtaFvta', 'asc');

		if( $fecha_desde ){
			$busqueda->whereBetween('VtaFvta', [ $fecha_desde, $fecha_hasta ]);			
		}

		// Tipo
		if( $tipo != "todos" ){				
			$busqueda->where('TidCodi', $tipo );
		}

		// Estado
		if( $estado != "todos" ){
			$busqueda->where('VtaFMail', $estado );
			// if( $estado == "anulado" ){
			// 	$busqueda->where('VtaEsta', 'A' );
			// }
			// else {
			// 	$busqueda->where('fe_rpta', $estado )->where('VtaEsta', 'V' );					
			// }
		}

		return $busqueda;
	}

	public function searchGetId( $empcodi , $fecha_desde , $fecha_hasta, $tipo = "todos", $estado = "todos" ){

		$data = $this->searchByFilter( $empcodi , $fecha_desde , $fecha_hasta, $tipo , $estado );
		
    return $data->pluck('VtaOper')->toArray();
	}

}