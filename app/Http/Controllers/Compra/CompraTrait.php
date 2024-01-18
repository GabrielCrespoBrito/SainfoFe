<?php

namespace App\Http\Controllers\Compra;
use App\Zona;
use App\Compra;
use App\EmpresaOpcion;
use App\SettingSystem;
use App\TipoCambioPrincipal;
use App\Models\MedioPago\MedioPago;
use App\Repositories\MedioPagoRepository;

trait CompraTrait 
{
  
  public function getSearch($request)
  {
		$term = $request->input('search')['value'];
		$busqueda = \DB::connection('tenant')->table('compras_cab')
		->join('prov_clientes' , function($join){
		  $join
		  ->on('prov_clientes.PCCodi', '=', 'compras_cab.PCcodi' )
		  ->on('prov_clientes.EmpCodi', '=', 'compras_cab.EmpCodi' )
		  ->where('prov_clientes.TipCodi', '=', 'P' );
		})        
		->join('condicion' , function($join){
			$join
			->on('condicion.conCodi', '=', 'compras_cab.concodi' )
			->on('condicion.empcodi', '=', 'compras_cab.EmpCodi' );
		})
		// ->join('vendedores' , function($join){
		// 	$join
		// 	->on('vendedores.Vencodi', '=', 'compras_cab.vencodi' )
		// 	->on('vendedores.empcodi', '=', 'compras_cab.EmpCodi' );
		// })
		->join('moneda' , 'moneda.moncodi' , '=' , 'compras_cab.MonCodi')
		->where('compras_cab.EmpCodi' , '=' , empcodi())
		->where('compras_cab.MesCodi' , '=' , $request->mes)
		->where('compras_cab.LocCodi' , '=' , $request->local)
		->select(
			'compras_cab.CpaOper' , 
			'compras_cab.CpaNume',
			'prov_clientes.PCNomb',
			'moneda.monnomb',
			'condicion.connomb',
			'compras_cab.TidCodi',
			'compras_cab.CpaFCpa',
			'compras_cab.User_Crea',
			'compras_cab.CpaImpo',
			'compras_cab.CpaPago',	
			'compras_cab.CpaSald',
			'compras_cab.CpaSdCa',
			'compras_cab.AlmEsta'
		);

		if( $request->estadoAlmacen ){
			// Si es pendiente, va a significa que sea mayor que 0.
			$filter = $request->estadoAlmacen == "Pe" ? '>' : '=';
		  $busqueda->where('compras_cab.CpaSdCa', $filter , 0 );
		}

		if( ! is_null($term) ){
		  $busqueda->orderBy('compras_cab.CpaOper', 'desc');            
		  $busqueda = $busqueda
		  ->where( 'prov_clientes.PCNomb' , 'LIKE' , '%' . $term . '%' )
		  ->orWhere( 'compras_cab.CpaNume' , 'LIKE' , '%' . $term . '%' )
		  ->get();
		}
		else {
		  $busqueda->orderBy('compras_cab.CpaOper', 'desc');                  
		}
    return $busqueda;
  }

	public function getDataForm( $accion , $id = null )
	{
		$empresa = get_empresa();
    $mprepository = new MedioPagoRepository( new MedioPago(), $empresa->empcodi );
    $data = [
			'monedas' => cacheHelper('moneda.all'),
      'grupos' => cacheHelper('grupo.all'),
			'igv_porc' => get_option('Logigv'),
      'igvOptions' => SettingSystem::getIgvOpciones(),
      'vendedores' => $empresa->vendedores,
      'forma_pagos' => $empresa->formas_pagos,
      'zonas' => Zona::all(),
      'medios_pagos'        => $mprepository->all()->where('uso', MedioPago::ESTADO_USO),
      'cursor_pointer_producto' => get_option(EmpresaOpcion::CAMPO_CURSOR_PRODUCTO),
      'tipo_cambio' =>  TipoCambioPrincipal::ultimo_cambio(false),
      'compra' => new Compra,
      'locales' => auth()->user()->locales,
    ];

    if($id) {
      $compra = Compra::with('items.producto.unidades_')->find($id);
      $data['igv_porc'] = $compra->items->first()->DetIgvv;
      $data['compra'] = $compra;
    }
    return $data;
	}
}