<?php

namespace App\Http\Controllers\Sunat;

use App\Venta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SunatGetStatusDocumentController extends Controller
{
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */

  // Solo pasaran los que no tengan resumen de anulación
  public function verifiyStatus(Request $request)
  {
    $documento = Venta::find($request->id);
    // Process Anulación loremp ipsum odlor
    // $response =  $documento->anulate();
    // return $response;		
  }
}
