<?php

namespace App\Util\Sunat\Request\wsdl;

class WsldNubeFactOse implements WsldInterface
{

  # En Nube una sola dirección para el envio de todos los documentos y consultas
  const ALLSERVICES_PRODUCTION = "https://ose.nubefact.com/ol-ti-itcpe/billService?wsdl";  
  const ALLSERVICES_BETA = "https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl";

  /**
   * Obtener ruta en producción y beta , para el envio de FACTURA, BOLETAS, NOTAS DE CREDITO/DEBITO
   * 
   * @return string
   */
  public function getBillServiceProduction()
  {
    return self::ALLSERVICES_PRODUCTION;
  }

  public function getBillServiceBeta()
  {
    return self::ALLSERVICES_BETA;
  }

  /**
   * Obtener ruta en producción y beta para el envio Guias de remsión
   * 
   * @return mixed
   */
  public function getBillServiceGuiaProduction()
  {
    return self::ALLSERVICES_PRODUCTION;
  }

  public function getBillServiceGuiaBeta()
  {
    return self::ALLSERVICES_BETA;
  }

  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds
   * 
   * @return mixed
   */
  public function getProductionConsult()
  {
    return self::ALLSERVICES_PRODUCTION;
  }


  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds y status
   * 
   * @return mixed
   */
  public function getProductionConsultStatus()
  {
    return self::ALLSERVICES_PRODUCTION;
  }

  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds y status
   * 
   * @return mixed
   */
  public function getProductionConsultCdr()
  {
    return self::ALLSERVICES_PRODUCTION;
  }

  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds y status
   * 
   * @return mixed
   */
  public function getBetaConsult()
  {
    throw new \Exception("Sunat No tiene el servicio de consulta para el ambiente de desarrollo", 1);
  }
  
}
