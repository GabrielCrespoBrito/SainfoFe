<?php

namespace App\Http\Controllers\Sunat;

use App\Venta;
use App\Resumen;
use App\Http\Controllers\Controller;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Http\Requests\VerificationDocumentoAnulationRequest;

class SunatAnulacionController extends Controller
{
  public function __construct()
  {
  }

	public function make(VerificationDocumentoAnulationRequest $request )
	{
    $this->authorize(p_name('A_ANULAR', 'R_VENTA'));

    $documento = Venta::find($request->id_factura );
    $is_anulado = false;

    if( $documento->isDocumentoSunat() && get_empresa()->produccion() ){

      $resumen = $documento->createResumenAnulacion();
      $resumen->enviarValidarTicket();    
      $resumen->refresh();

      // Si no habia resumen antes
      if( $resumen->DocCEsta == Resumen::PENDIENTE_STATE  ||  $resumen->DocCEsta == Resumen::PROCESANDO_STATE ){              
        $resumen->DocCEsta == Resumen::PENDIENTE_STATE ? 
          $resumen->enviarValidarTicket() :
          $resumen->validateTicket();
      }

      $documento->searchSunatGetStatus(true);
      $is_anulado = $documento->isAnulada();
    }

    else {
      $documento->updateStatusCode(StatusCode::EXITO_0003['code']);
      $documento->saveStatus0003();
      $documento->anularPago();
      $is_anulado = true;
    }

    $title = $is_anulado ? 'Anulación exitosa' : 'Anulación con error';
    $message = $is_anulado ? 'El documento ha sido anulado exitosamente' : 'El documento ha sido anulado exitosamente, por favor comunicarse con el responsable del sistema';
    $type = $is_anulado ? 'success' : 'error';
    notificacion( $title, $message , $type, ['hideAfter' => false]);
	}

}