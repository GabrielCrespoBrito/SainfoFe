<?php
namespace App\Util\Sunat\Services;


/**
 * Clase para facilitar el manejo de parametros a los servicios de la sunat 
 */

class ServicesParams
{
  /**
   * Obtener formato de los parametros para de la consulta de un documento
   *
   * @param int $ruc Ruc del cliente
   * @return array  
   */
  public static function getFormatGetStatus( $ruc, $td, $serie, $numero )
  {
    return [[
      'rucComprobante' => $ruc,
      'tipoComprobante' => $td,
      'serieComprobante' => $serie,
      'numeroComprobante' =>  $numero,
    ]];
  }

  /**
   * Obtener el formato de los parametros para de la consulta de ticket
   * 
   * @param string $ticket 
   * @return array  
   */
  public static function getFormatTicket( string $ticket)
  {
    return [[
      'ticket' => $ticket,
    ]];
  }

  /**
   * Obtener el formato de los parametros para de el envio del resumen diario
   * 
   * @param string $fileName
   * @param string $fileName
   * @return array  
   */
  public static function getFormatSummary(string $fileName, $contentFile)
  {
    return [[
      'fileName' => $fileName,
      'contentFile' => $contentFile,
    ]];
  }  


}
