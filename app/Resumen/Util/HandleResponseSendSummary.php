<?php

namespace App\Resumen\Util;

use App\Util\Sunat\Request\ResolverWsld;

class HandleResponseSendSummary extends HandleResponse
{
  /**
   * Procesar la respuesta
   */  
  public function processResponse()
  {

    if( $this->clienteCommunicateFails()){
      $this->establishClienteError();
      return;
    }
    else if( $this->serviceCommunicateFails()){
      $this->establishCommunicateError();
      return;
    }

    $this->saveTicket();
    $this->setSuccessCommunicate();
  }

  public function setSuccessCommunicate()
  {
    $message = 'Ticket generado exitosamente' . ' ' . $this->getCommunicate()['commnucate_data']->ticket;
    $this->setMessageCode($message, true);
  }

  public function saveTicket()
  {
    $ticket = $this->getCommunicate()['commnucate_data']->ticket;
    $this->resumen->saveTicket($ticket);
  }

  public function establishCommunicateError()
  {
    $data = $this->getCommunicate()['commnucate_data'];

    if( $this->getProveedor() == ResolverWsld::NUBEFACT ){

      if( get_class($data) == "SoapFault" ){

        $code = explode('.', $data->faultcode);
        $code = end($code);
        $message = '(' . $code . ') ' . $data->faultstring; 
      }
      else {
        $message = "Error imprevisto comuniqueme con el administrador";
      }
    }


    if ($this->getProveedor() == ResolverWsld::SUNAT ) {

      if (get_class($data) == "SoapFault") {
        $code = $data->faultcode;
        $message = '(' . $code . ') ' . $data->faultstring;
      }
      else {
        $message = "Error imprevisto comuniqueme con el administrador";
      }
    }

    $this->setMessageCode($message, false, $code );

    $updateInfo = [
      'DocDesc' => $message
    ];

    if( is_numeric($code)  ){
      $updateInfo['DocCEsta'] = $code;
    }

    $this->resumen->update($updateInfo);
  }
}