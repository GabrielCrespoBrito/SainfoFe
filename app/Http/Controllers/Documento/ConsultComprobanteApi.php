<?php

namespace App\Http\Controllers\Documento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ConsultComprobante;
use App\VentaCompartida;

class ConsultComprobanteApi extends Controller
{


  public function consult( Request $request, $token, $documento )
  {
    $consult = new ConsultComprobante($token, $documento);
    $consult->handle();

    if( $consult->success ){
      return response()->file($consult->path);
    }

    return $consult->error;
  }

  public function compartir( $documento )
  {
    response()->json([
      'success' => VentaCompartida::compartir($documento)
    ]);
  }
}

