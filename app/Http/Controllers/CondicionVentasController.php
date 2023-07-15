<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\CondicionVenta;
use App\Repositories\CondicionCompraVentaRepository;

class CondicionVentasController extends Controller
{
	public function saveDefault( Request $request )
	{
		if( $request->type == 'cotizacion' ){
			CondicionVenta::saveDefaultCotizacion($request->descripcion);
		}

    if ( $request->type == 'orden_compra' ) {
      CondicionVenta::saveDefaultOrdenCompra($request->descripcion);
    }

		else {
			CondicionVenta::saveDefault($request->descripcion);			
		}
    // Routing
    (new CondicionCompraVentaRepository(new CondicionVenta(), empcodi()))->clearCache();

	}

}