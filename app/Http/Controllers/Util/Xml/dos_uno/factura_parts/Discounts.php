<?php 

namespace App\Http\Controllers\Util\Xml\dos_uno\factura_parts;

use App\TipoDocumentoPago;
use App\Util\XmlInformation\XmlInformation;
use App\Venta;

/*
  Cronograma:
  1. Hacer Cronograma
  2. Metas de la semana
  3. 
*/

trait Discounts 
{
  public function getDescuentoItem($item , $descuento, $valor )
  {
    return $this->getDescuentoBase(
      $item->xmlInfo()->getIndicator(),
      $item->xmlInfo()->getRazonCode(),
      $item->descuentoFactor(),
      $descuento,
      $valor
    );
  }

  public function getDescuento( $documento )
  {
    $total = $documento->totales_documento;
    $tipo_cargo = (int) $documento->VtaSPer;
    
    if( $tipo_cargo == Venta::CARGO_DESCUENTO ){
      $total_cargo_descuento = $total->descuento_global;
      $valor = $total->total_valor_venta;
      $indicator =  XmlInformation::INDICADOR_DESCUENTO;
      $razonCode = XmlInformation::DESCUENTO_GLOBALES_AFECTA_IGV;
    }

    else if( $tipo_cargo == Venta::CARGO_PERCEPCION ){
      $total_cargo_descuento = $total->percepcion;
      $valor = $total->total_base_percepcion;
      $indicator =  XmlInformation::INDICADOR_CARGO;
      $razonCode = XmlInformation::PERCEPCION_VENTA_INTERNA;
    }

    if( $tipo_cargo == Venta::CARGO_RETENCION )
    {
      $total_cargo_descuento = $total->retencion;
      $valor = $total->total_cobrado;
      $indicator =  XmlInformation::INDICADOR_DESCUENTO;
      $razonCode = XmlInformation::RETENCION_APLICADO;
    }

    $descuento_porc = $documento->descuentoFactor();

    return $this->getDescuentoBase(
      $indicator,
      $razonCode,
      $descuento_porc,
      $total_cargo_descuento,
      $valor
    );
  }

  public function getDescuentoAnticipo( $documento )
  {
    $indicator = XmlInformation::INDICADOR_DESCUENTO;
    $razonCode = XmlInformation::DESCUENTO_ANTICIPO;

    $descuento_porc = 1;
    $total_cargo_descuento = $this->documento->VtaTotalAnticipo;
    $valor = $total_cargo_descuento;

    return $this->getDescuentoBase(
      $indicator,
      $razonCode,
      $descuento_porc,
      $total_cargo_descuento,
      $valor
    );
  }

  /**
   * Parte donde poner el total del cargo por descuento o percepción
   * 
   * @return void
   */
  public function getDescuentoGlobalPart()
  {
    $xml = "";

    if( $this->tipo_documento == TipoDocumentoPago::NOTA_CREDITO || $this->tipo_documento == TipoDocumentoPago::NOTA_DEBITO ){
      $this->change_datas([["descuento_global", $xml]], $this->descuentoGlobal_XMLPART);
    }

    # Si tiene descuento o cargo global
    if( $this->documento->hasGlobalCargo() ) {
      $xml = $this->getDescuento($this->documento);
    }

    elseif( $this->documento->hasAnticipo() ){
      $xml = $this->getDescuentoAnticipo($this->documento);
    }

    $this->change_datas([["descuento_global", $xml]], $this->descuentoGlobal_XMLPART);
  }  
  
}