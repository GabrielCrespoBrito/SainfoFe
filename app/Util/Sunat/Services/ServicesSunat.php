<?php

namespace App\Util\Sunat\Services;

/**
 * Clase para obtener el nombre de los servicios
 */

class ServicesSunat 
{
  /**
   * Servicios
   */
  const GET_STATUS = 'getStatus';
  const GET_STATUS_CDR = 'getStatusCdr';
  const SEND_SUMMARY = 'sendSummary';
  const SEND_BILL = 'sendBill';

  const SERVICES = [
    self::GET_STATUS , 
    self::GET_STATUS_CDR, 
    self::SEND_SUMMARY , 
    self::SEND_BILL
  ];

  /**
   * Verificar que el servicio pasado es valid
   *
   * @param string $validService
   * @return bool
   */
  public static function validService($validService)
  {
    return in_array($validService, self::SERVICES);
  }


}
