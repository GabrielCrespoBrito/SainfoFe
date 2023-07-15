<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportVentaRequest;
use App\Venta;
use App\Cotizacion;

class ImportacionesController extends Controller
{
  public function getCotizacion( ImportVentaRequest $request )
  {  		
		$nume = $request->serie_documento . '-' . $request->numero_documento;			
		$documento = Cotizacion::findByNume($nume) ?? Venta::findByNume($nume);		
		$items = [];
		$cliente_info = [];

		// Cliente
		$cliente = $documento->cliente;
		$cliente_info['PCRucc']  = $cliente->PCRucc;
		$cliente_info['PCNomb']  = $cliente->PCNomb;
		$cliente_info['PCDire']  = $cliente->PCDire;
		$cliente_info['PCMail']  = $cliente->PCMail;
		// $cliente_info['VenCodi'] = $cliente->vencodi;

		// Items
		foreach( $documento->items as $item ){				
			$data = [];
      $data['Unidades'] = $item->producto->unidades;
      $data['Marca'] = $item->MarNomb;
      $data['MarCodi'] = $item->producto->marcodi;
      $data['TieCodi'] = $item->producto->tiecodi;
      $data['DetPeso'] = $item->producto->ProPeso;
      $data['DetCome'] = $item->DetCome;
      $data['UniCodi'] = $item->UniCodi;
      $data['DetNomb'] = $item->DetNomb;
      $data['DetUni']  = $item->DetUnid;
      $data['DetUniNomb'] = $item->DetUnid;
      $data['DetCant'] = $item->DetCant;
      $data['DetPrec'] = $item->DetPrec;
      $data['DetDcto'] = $item->DetDcto;
      $data['DetPercP'] = $item->DetPercP;
      $data['DetBase'] = $item->DetBase;
      $data['DetIGVP'] = $item->DetIGVP ?? 0;
      $data['DetPvta'] = 0;      
      $data['DetIGVV'] = $item->DetIGVV;
      $data['DetISC']  = $item->DetISC;
      $data['DetISP']  = $item->DetISP;
      $data['DetImpo'] = $item->DetImpo;
      $data['DetPercV'] = $item->DetPercV;
			array_push( $items, $data );
		}

		$data = [
			'items' => $items, 
			'moneda' => $documento->getMoneda(), 
			'table' => $documento->table,
			'cliente' => $cliente_info,
		];

		return $data;
  }

  public function search()
  {
    return datatables()->of( 
      Cotizacion::query()
      ->with(['moneda','usuario','cliente'])
      ->where('EmpCodi',session('empresa'))
      ->where('cotesta','p') 
    )->toJson();
  } 

  public function create()
  {
    return view('cotizaciones.crear_modificar');
  }
}
