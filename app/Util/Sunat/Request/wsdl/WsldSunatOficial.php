<?php
namespace App\Util\Sunat\Request\wsdl;

use App\OpcionUrl;

class WsldSunatOficial implements WsldInterface
{
  # Envio de de documentos
  const BILLSERVICE_BETA = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl";

  # Envio de guias 
  const BILLSERVICE_GUIA_PRODUCTION = "https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService?wsdl";

  const BILLSERVICE_GUIA_BETA = "https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService?wsdl";

  # Consulta de CDR y Status
  const BILLCONSULT_PRODUCTION = "https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl";

  /**
   * Obtener ruta en producción y beta , para el envio de FACTURA, BOLETAS, NOTAS DE CREDITO/DEBITO
   * 
   * @return string
   */
  public function getBillServiceProduction()
  {
    return public_path(file_build_path('static', 'webservice', 'production.xml'));
  }
  
  public function getBillServiceBeta()
  {
    return self::BILLSERVICE_BETA;
  }

  /**
   * Obtener ruta en producción y boleta, para el envio Guias de remsión
   * 
   * @return mixed
   */
  public function getBillServiceGuiaProduction()
  {
    return self::BILLSERVICE_GUIA_PRODUCTION;
  }
  
  public function getBillServiceGuiaBeta()
  {
    return self::BILLSERVICE_GUIA_BETA;

  }

  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds y status
   * 
   * @return mixed
   */
  public function getProductionConsult()
  {
    return self::BILLCONSULT_PRODUCTION;
  }

  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds y status
   * 
   * @return mixed
   */
  public function getProductionConsultStatus()
  {
    return public_path(file_build_path('static', 'webservice', 'production.xml'));
  }

  /**
   * Obtener ruta en produccion, para la consulta de tickets, crds y status
   * 
   * @return mixed
   */
  public function getProductionConsultCdr()
  {
    return self::BILLCONSULT_PRODUCTION;
  }
  

  public function getBetaConsult()
  {
    throw new \Exception("Sunat No tiene el servicio de consulta para el ambiente de desarrollo", 1);   
  }  
}