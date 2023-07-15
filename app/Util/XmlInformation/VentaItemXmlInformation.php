<?php

namespace App\Util\XmlInformation;

class VentaItemXmlInformation extends XmlInformation
{
  /**
   * Obtener indicar dependiendo del cargo
   * 
   * @return String
   */
  public function getIndicator(): String
  {
    return self::INDICADOR_DESCUENTO;
  }

  /**
   * Codigo de tipo de descuento o narco: ANEXO 583
   * 
   * @return String
   */
  public function getRazonCode() : String
  {
    return self::OTROS_DESCUENTO;
  }

  /**
   * Codigo de tipo de descuento o narco: ANEXO 583
   * 
   * @return String
   */
  public function getTipoISC(): String
  {
    return self::ISC_PRICE_TIPO['SISTEMA_VALOR'];
  }

  /**
   * Códigos – Tipo de Precio de Venta Unitario - Catalogo 16
   * 
   * @return String
   */
  public function getTipoPrecio(): String
  {
    return $this->model->isGratuito() ? self::PRECIO_NO_ONOROSA : self::PRECIO_UNITARIO_IGV;
  }



}
