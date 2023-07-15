<?php

namespace App\Util\Sunat\Services;

use App\Util\Sunat\Services\SunatCommunicate;

/**
 * Clave para consultar el status/ticket o CDR, de facturas, notas de credito y debito
 * 
 */

abstract class SunatConsultDocument  extends SunatCommunicate
{
  public function validation($data)
  {
    return true;
  }

  /**
   * Transformar la array pasado por parametro a el formato aceptado de la sunat
   * 
   * @example []
   * @return array
   */
  public function getParametersFormat(array $data ) 
  {

  }

  /**
   * Transformar la array pasado por parametro a el formato aceptado de la sunat
   * 
   * @example []
   * @return array
   */
  public function getParametersFormatDocument(array $data)
  {
    $data = $data[0];
    return [
      [
        'rucComprobante'   => $data['ruc'],
        'tipoComprobante'  => $data['tipo_documento'],
        'serieComprobante' => $data['serie'],
        'numeroComprobante' => $data['numero'],
      ]
    ];
  }

  public function getParametersFormatTicket(array $data)
  {
    $data = $data[0];
    return [ ['ticket'   => $data['ticket'] ];
  }




}
