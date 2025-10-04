<?php
namespace App\Util\ConsultAgenteRetencion;

use App\Util\ConsultAgenteRetencion\ConsultAgenteRetencionInterface;

class ConsultAgenteRetencionMigo implements ConsultAgenteRetencionInterface
{
  public function consult($ruc)
  {
    $success = false;
    $data_response = [];
    $error = '';

    $curl = curl_init();
    $data = [
      'token' =>  config('credentials.migo.token'),
      'ruc' => $ruc
    ];

    $post_data = http_build_query($data);

    curl_setopt_array($curl, array(
      CURLOPT_URL => config('credentials.migo.url_agente_retencion'),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $post_data,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
      $error = "Error al consultar documento, por favor ingrese los datos manualmente";
    } 
    
    else {
      $responseArr = json_decode($response);
      $success = optional($responseArr)->success ?? false;

      if ($success == true) {
        $data_response['a_partir_del'] = $responseArr->a_partir_del;
        $data_response['resolucion'] = $responseArr->resolucion;
      } else {
        $error = optional($responseArr)->message ?? 'Recurso no encontrado.';
      }
    }

    return [
      'data' => $data_response,
      'error' => $error,
      'success' => $success,
    ];
  }
}
