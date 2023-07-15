<?php

namespace App\Models\Venta\Attribute;

trait VentaItemAttribute
{

  public function getVtaFvtaReverseAttribute()
  {
    $date = explode('-', $this->VtaFvta);
    return str_concat('/', $date[2], $date[1], $date[0]);
  }

  public function getVtaPagoAttribute($value)
  {
    return $this->dec($value);
  }

  public function setVtaTcamAttribute($value)
  {
    $this->attributes['VtaTcam'] = decimal($value, 3);
  }

  public function getdescuentoAttribute()
  {
    return $this->VtaDcto;
  }

  public function getimporteAttribute()
  {
    return (float) $this->VtaImpo;
  }

  public function getpercepcionAttribute()
  {
    return fixedValue($this->VtaPerc);
  }

  public function getcorrelativeAttribute()
  {
    return $this->VtaSeri . '-' . $this->VtaNumee;
  }

  public function setVtaNumeAnticipoAttribute($val)
  {
    $this->attributes['VtaNumeAnticipo'] = strtoupper($val);
  }

  public function getFechaAttribute()
  {
    return $this->VtaFvta;
  }

  public function getIdAttribute()
  {
    return $this->VtaOper;
  }

  public function getTipocambioAttribute()
  {
    return $this->VtaTcam;
  }

  public function getDetISCAttribute($val)
  {
    return (float) $val;
  }

  public function getDetCantAttribute($value)
  {
    return (float) $value;
  }

  public function getcantidadAttribute()
  {
    return $this->DetCant;
  }

  public function getDetPrecAttribute($value)
  {
    // return $this->dec($value);
    return $value;
  }

  public function getDetDctoAttribute($value)
  {
    return $this->dec($value);
  }

  public function getNombreDetalleAttribute()
  {
    return trim("{$this->DetNomb} {$this->DetDeta}");
  }

  public function getISCFactorAttribute()
  {
    return math()->porcFactor($this->DetISCP);
  }

  public function getISCValorAttribute()
  {
    return $this->DetISC;
  }

  public function setLoteAttribute( array $totales)
  {
    $this->attributes['lote'] = json_encode([
      'precio_unitario' => fixedValue($totales['precio_unitario'], 4),
      'valor_unitario'  =>  fixedValue($totales['valor_unitario'],10),
      'valor_noonorosa' => fixedValue($totales['valor_noonorosa']),
      'valor_venta_bruto' => fixedValue($totales['valor_venta_bruto']),
      'valor_venta_por_item' => fixedValue($totales['valor_venta_por_item']),
      'valor_venta_por_item_igv' => fixedValue($totales['valor_venta_por_item_igv']),
      'impuestos_totales' => fixedValue($totales['impuestos_totales']),
    ]);
  }  
}