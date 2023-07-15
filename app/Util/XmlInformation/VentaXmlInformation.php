<?php

namespace App\Util\XmlInformation;

class VentaXmlInformation extends XmlInformation
{
  /**
   * Obtener indicar dependiendo del cargo
   * 
   * @return String
   */
  public function getIndicator(): String
  {
    return $this->getModel()->hasDescuento() ? self::INDICADOR_DESCUENTO : self::INDICADOR_CARGO;
  }

  /**
   * Codigo de tipo de descuento o narco: ANEXO 583
   * 
   * 
   */
  public function getRazonCode()
  {
    return $this->getModel()->hasDescuento() ? self::DESCUENTO_GLOBALES_AFECTA_IGV : self::PERCEPCION_VENTA_INTERNA;
  }

  /**
   * Codigo del tipo de operación
   * 
   */
  public function getTipoOperacion()
  {
    if( $this->getModel()->hasDetraccion() ) {
      return self::TIPOS_OPERACION['OPERACION_SUJETA_DETRACCION'];
    }

    if( $this->getModel()->hasMontoPercepcion() ){
      return self::TIPOS_OPERACION['VENTA_PERCEPCION'];
    }

    return self::TIPOS_OPERACION['VENTA_INTERNA'];
  }

  /**
   * Codigo de status del documento de referencia
   * 
   */
  public function getDocumentReferenceStatus()
  {
    return "1";
  }

  public function getDocumentReferenciaTipo()
  {
    return $this->getModel()->docReferenciaIsFactura() ? self::TIPO_DOCUMENTO_RELACIONADO['FACTURA_ANTICIPO'] : self::TIPO_DOCUMENTO_RELACIONADO['BOLETA_ANTICIPO'];
  }

  public function getTipoOperacionSunat()
  {
    return "01";
  }


  


}