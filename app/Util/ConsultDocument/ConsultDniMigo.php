<?php

namespace App\Util\ConsultDocument;

use Illuminate\Support\Facades\Log;
use App\Util\ConsultDocument\ConsultDniInterface;

class ConsultDniMigo implements  ConsultDniInterface
{
  public function consult($dni)
  {
    $success = false;
    $data_response = [];
    $error = '';

    $curl = curl_init();

    $data = [
      'token' =>  config('credentials.migo.token'),
      'dni' => $dni
    ];

    $post_data = http_build_query($data);

    curl_setopt_array($curl, array(
      CURLOPT_URL =>  config('credentials.migo.url_dni'),
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
      $success = $responseArr->success;

      if($success){
        $data_response['razon_social'] = $responseArr->nombre;
      }
      else {
        $error = $responseArr->message;
      }
    }

    return [
      'data' => $data_response,
      'error' => $error,
      'success' => $success,
    ];

  }
}
