<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoDocumentoPago;
use App\ClienteProveedor;

class TipoDocumentoPagoController extends Controller
{
  public function search()
  {
    return datatables()->of(TipoDocumentoPago::query())->toJson();
  }	

  public function busqueda( Request $request )
  {
  	return $tipo_documento = TipoDocumentoPago::find($request->codigo);
  }

  public function serie_consultar( Request $request )
  {
    $cliente = ClienteProveedor::find($request->ruc_cliente);
    
    $query = 
    $cliente->ventas
    ->where('TidCodi', $request->tipo_documento)
    ->where('VtaSeri', strtoupper($request->codigo));

    return datatables()->of( $query )->toJson();
  }
}