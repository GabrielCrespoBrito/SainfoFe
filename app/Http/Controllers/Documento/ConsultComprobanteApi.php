<?php

namespace App\Http\Controllers\Documento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ConsultComprobante;

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
}

