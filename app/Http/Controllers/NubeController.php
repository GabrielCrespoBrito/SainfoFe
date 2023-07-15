<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;

class NubeController extends Controller
{
  # Documentos
  public function index()
  {
    return view('nube.index');
  }

  # Documentos de search
  public function search(Request $request)
  {
    $busqueda = Venta::query()->with(['nube']);

    if( $request->estado != 'all' ){

      $busqueda = \DB::table('ventas_cab')
      ->join('ventas_nube', 'ventas_cab.VtaOper', '=', 'ventas_nube.VtaOper')
      ->where('ventas_nube.Estatus', $request->estado )        
      ->select('*')
      ->get();
    }
    
    return datatables()->of( $busqueda )->toJson(); 
  }

  public function respaldar_documento( Request $request )
  {
    $saves_status = Venta::find($request->id_factura)->saveAmazon();
    return "Se ha guardado correctamente";
  }
}