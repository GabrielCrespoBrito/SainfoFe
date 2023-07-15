<?php

namespace App\Util\ConsultDocument;

use Illuminate\Support\Facades\Log;

class ConsultRucMigo implements ConsultRucInterface
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
      CURLOPT_URL => config('credentials.migo.url_ruc'),
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
      $success = optional($responseArr)->success;
      
      if ($success) {
        $data_response['razon_social'] = $responseArr->nombre_o_razon_social;
        $data_response['ubigeo'] = $responseArr->ubigeo;
        $data_response['ubigeo_nombre'] = "({$responseArr->ubigeo}) - {$responseArr->departamento} - {$responseArr->provincia} - {$responseArr->distrito}";
        $data_response['direccion'] = $responseArr->direccion; 
      } else {
        $error = optional($responseArr)->message ?? 'Error al consultar documento ';
      }
    }

    return [
      'data' => $data_response,
      'error' => $error,
      'success' => $success,
    ];

  }
}
