<?php

namespace App\Resumen\Util;

use App\Resumen;

abstract class HandleResponse
{
  protected $response = [
    'success' => true,
    'message' => '',
    'code' => '',
  ];

  protected $resumen;
  protected $communicate;
  protected $proveedor;

  public function __construct(Resumen $resumen, array $communicate, $proveedor )
  {
    $this->resumen = $resumen;
    $this->communicate = $communicate;
    $this->proveedor = $proveedor;
  }

  /**
   * Get the value of resumen
   */
  public function getResumen()
  {
    return $this->resumen;
  }

  /**
   * Set the value of resumen
   *
   * @return  self
   */
  public function setResumen($resumen)
  {
    $this->resumen = $resumen;

    return $this;
  }

  /**
   * Get the value of communicate
   */
  public function getCommunicate()
  {
    return $this->communicate;
  }

  /**
   * Set the value of communicate
   *
   * @return  self
   */
  public function setCommunicate($communicate)
  {
    $this->communicate = $communicate;

    return $this;
  }

  /**
   * Si la comunicación con el cliente fallo
   * 
   * @return bool
   */

  public function clienteCommunicateFails()
  {
    return $this->getCommunicate()['client_connection_success'] == false;
  }

  public function getClienteMessageError()
  {
    return $this->getCommunicate()['client_connection_response']['error'];
  }


  public function setMessageCode( $message, $status = true , $code = null)
  {
    $this->setMessage($message);
    $this->setStatus($status);
    $this->setCode($code);
  }

  public function establishClienteError()
  {
    $client = $this->getCommunicate()['client_connection_response'];
    $this->setMessage( $client['error'] );
    $this->setErrorStatus();
  }

  public function setMessage($message)
  {
    $this->response['message'] =$message;
  }

  public function setCode($code)
  {
    $this->response['code'] = $code;
  }


  public function setErrorStatus()
  {
    $this->setStatus(false);
    return $this;
  }

  public function setStatus( bool $status)
  {
    $this->response['success'] = $status;

    return $this;
  }

  public function serviceCommunicateFails()
  {
    return $this->getCommunicate()['communicate'] == false;
  }

  /**
   * Get the value of proveedor
   */ 
  public function getProveedor()
  {
    return $this->proveedor;
  }

  /**
   * Set the value of proveedor
   *
   * @return  self
   */ 
  public function setProveedor($proveedor)
  {
    $this->proveedor = $proveedor;

    return $this;
  }

  public function getResponse()
  {
    return (object) $this->response;
  }
  
}