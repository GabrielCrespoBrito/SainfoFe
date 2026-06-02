<?php

namespace App\Jobs\GuiaSalida;

use Exception;
use App\GuiaSalida;
use GuzzleHttp\Client;
use App\Util\ResultTrait;
use GuzzleHttp\Exception\ClientException;

class SendApi
{
  use ResultTrait;

  protected $token;
  protected $empresa;
  protected $ticket;
  protected $guiaSalida;

  public function __construct(GuiaSalida $guiaSalida)
  {
    $this->guiaSalida = $guiaSalida;
    $this->empresa = get_empresa();
  }

  public function generateToken()
  {
    $res = $this->empresa->getOrGenerateGuiaTokenApi();

    if ($res->success) {
      $this->token = $res->data['token'];
      return true;
    }

    return $this->setError($res->data);
  }


  public function sendEnvio()
  {
    $client = new Client(['verify' => false]);
    $options = [
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => sprintf("Bearer %s", $this->token),
      ],
      'json' => [
        'archivo' => $this->guiaSalida->getDataEnvioApi()
      ]
    ];

    $url =  sprintf(
      "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/%s-%s-%s-%s",
      $this->empresa->ruc(),
      $this->guiaSalida->getTipoDocumento(),
      $this->guiaSalida->GuiSeri,
      $this->guiaSalida->GuiNumee,
    );

    try {
      $res = $client->post($url, $options);
      $content = json_decode($res->getBody()->getContents());
      
      $this->guiaSalida->saveTicket($content->numTicket, $content->fecRecepcion);
      $this->ticket = $content->numTicket;
      
      logger(sprintf('@GUIA-SUNAT %s %s SendApi', $this->guiaSalida->EmpCodi, $this->guiaSalida->GuiUni, 
      [$content]));
      return true;
    } catch (ClientException $th) {
      logger()->error(sprintf('@GUIA-SUNAT ERROR SEND API %s %s %s' , $this->guiaSalida->EmpCodi, $this->guiaSalida->GuiUni, $th->getMessage()));
      $infoError = json_decode($th->getResponse()->getBody()->getContents());
      if (property_exists($infoError, 'status')) {
        $cod = $infoError->status;
        $msg = $infoError->message;
      } 
      else if(property_exists($infoError, 'cod')){
        $cod = $infoError->cod;
        $msg = $infoError->msg;
        if(property_exists($infoError, 'errors')){
          foreach( $infoError->errors as $error ){
            $msg .= sprintf(" (%s - %s) ", $error->cod, $error->msg);
          }
        }
      }
      else {
        $cod = "-000";
        $msg = $th->getMessage();
      }
      logger()->error(sprintf('@GUIA-SUNAT ERROR SEND API ClientException MESSAGE %s %s %s' , $this->guiaSalida->EmpCodi, $this->guiaSalida->GuiUni, $msg));
      return $this->setError(sprintf('Cod: %s | %s', $cod, $msg));
    } catch (Exception $th) {
      logger()->error(sprintf('@GUIA-SUNAT ERROR SEND API Exception %s %s %s' , $this->guiaSalida->EmpCodi, $this->guiaSalida->GuiUni, $th->getMessage()));
      return $this->setError($th->getMessage());
    }
  }

  public function handle()
  {
    if (!$this->generateToken()) {
      return $this;
    }

    if( $this->guiaSalida->fe_rpta >= "9" && $this->guiaSalida->fe_rpta != "98" ){
      if (!$this->sendEnvio()) {
        return;
      }
    }

    $res = $this->guiaSalida->validateTicket();
    $this->result['success'] = $res->success;
    $this->result['data'] = $res->data;
  }
}
