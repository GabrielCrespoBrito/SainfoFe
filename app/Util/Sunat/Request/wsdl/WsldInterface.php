<?php
namespace App\Util\Sunat\Request\wsdl;

interface WsldInterface
{
  /**
   * Obtener ruta en producción y beta , para el envio de Facturas, Boletas, Notas de credito/Debito
   * 
   * @return mixed
   */
  public function getBillServiceProduction();
  public function getBillServiceBeta();

  /**
   * Obtener ruta en producción y beta, para el envio Guias de remsión
   * 
   * @return mixed
   */
  public function getBillServiceGuiaProduction();
  public function getBillServiceGuiaBeta();


  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds
   * 
   * @return mixed
   */
  public function getProductionConsult();
  public function getBetaConsult();  


}