<?php

namespace App\Http\Controllers\Sunat;

use App\Sunat;
use App\Venta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Venta\ConsultStatusRequest;

class DocumentConsultStatusController extends Controller
{
  public function __construct()
  {
  }


  public function consult(ConsultStatusRequest $request, $id, $cdr = 0)
  {
    return self::consultStatic($id);
  }


  public static function consultStatic($id)
  {
    $doc = Venta::find($id);
    $consult = $doc->searchSunatGetStatus(true);
    $hasError = !$consult['client_connection_response']['status'] || !$consult['communicate'];
    if ($hasError) {
      $error = $consult['client_connection_response']['error'] ?
      $consult['client_connection_response']['error'] :
      'Error a la hora de consultar a la sunat';
      return response(['message' => $error], 400);
    }
    // 
    $message = sprintf(
      "%s : %s %s",
      $doc->numero(),
      $consult['commnucate_data']->status->statusCode,
      $consult['commnucate_data']->status->statusMessage
    );

    return response(['message' => $message], 200);
  }

  public function consultProcess($id)
  {
    $doc = Venta::find($id);

    $consult = $doc->searchSunatGetStatus(true);
    $success = !(!$consult['client_connection_response']['status'] || !$consult['communicate']);

    if (!$success) {
      $message = $consult['client_connection_response']['error'] ?
        $consult['client_connection_response']['error'] :
        'Error a la hora de consultar a la sunat';
    } else {

      $message =
        "{$doc->numero()} : " .
        $consult['commnucate_data']->status->statusCode . '' .
        $consult['commnucate_data']->status->statusMessage;
    }

    return [
      'message' => $message,
      'success' => $success
    ];
  }


  public function consultCDR(ConsultStatusRequest $request, $id, $cdr = 0)
  {
    $doc = Venta::find($id);
    $consult = $doc->searchSunatGetStatusCDR();

    ob_end_clean();
    ob_start();

    $hasError = !$consult['client_connection_response']['status'] || !$consult['communicate'];
    if ($hasError) {
      $error = $consult['client_connection_response']['error'] ?
        $consult['client_connection_response']['error'] :
        'Error a la hora de consultar a la sunat';
      notificacion($error, '', 'error');
      return back();
    }

    $data = $consult['commnucate_data'];

    if ($data->cdr_exists) {
      // return response()->file($data->cdr_info->path);
      return response()->download($data->cdr_info->path, $data->cdr_info->fileName);
    }

    $message = sprintf(
      '%s , Codigo: %s',
      $data->statusCdr->statusMessage,
      $data->statusCdr->statusCode
    );

    notificacion($message, '', 'success');
    session()->flash('N_hideAfter', 5000);
    return back();
  }
}
