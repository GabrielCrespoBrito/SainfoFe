<?php

namespace App\Jobs\GuiaSalida;

use Exception;
use App\GuiaSalida;
use GuzzleHttp\Client;
use App\Util\ResultTrait;
use GuzzleHttp\Exception\ClientException;

class ValidateTicket
{
  use ResultTrait;

  public $guiaSalida;
  public $token;
  public $reenviado = false;

  public function __construct(GuiaSalida $guiaSalida, $token = null)
  {
    $this->guiaSalida = $guiaSalida;
    $this->token = $token;
  }

  public function generateToken()
  {
    if ($this->token) {
      return $this->token;
    }

    $res = get_empresa()->getOrGenerateGuiaTokenApi();

    if ($res->success) {
      $this->token = $res->data['token'];
      return true;
    }

    return $this->setError($res->data);
  }

  public function handle()
  {
    $this->generateToken();

    if (! $this->token ) {
      return $this;
    }

    $client = new Client();
    $options = [
      'headers' => [
        'Authorization' => sprintf("Bearer %s", $this->token)
      ]
    ];

    $url =  sprintf("https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/%s", $this->guiaSalida->fe_ticket);

    try {
      $res = $client->get($url, $options);
      $content = json_decode($res->getBody()->getContents());
      $processor = $this->guiaSalida->apiResponseProcess($content);
      $result = $processor->getResult();
      $this->result['success'] = $result->success;
      $this->result['data'] = $result->data;

      // Reenviar
      if($content->codRespuesta == "98" && $this->reenviado == false ){
        $this->handle();
        $this->reenviado = true;
      }
    } catch (ClientException $th) {
      $infoError = json_decode($th->getResponse()->getBody()->getContents());
      if (property_exists($infoError, 'status')) {
        $cod = $infoError->status;
        $msg = $infoError->message;
      } else {
        $cod = $infoError->cod;
        $msg = $infoError->msg;
      }
      $this->setError(sprintf('Cod: %s | %s', $cod, $msg));
    } catch (Exception $th) {
      $this->setError($th->getMessage());
    }

    return $this;
  }

}