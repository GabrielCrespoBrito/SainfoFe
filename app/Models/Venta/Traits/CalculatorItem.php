<?php

namespace App\Models\Venta\Traits;

use App\Venta;
use Exception;

class CalculatorItem
{
  public $item;
  public $has_value = false;
  public $rounded = 2;

  // Igvs
  public $igv;
  public $igv_bace_cero;
  public $igv_bace_uno;
  public $bolsa_unidad;

  public $precio;
  public $cantidad;
  public $incluye_igv;
  public $base_imponible;
  public $is_gravado;
  public $is_bolsa;
  public $isc_porc;
  public $factor;
  public $is_sol;

  public $descuento_porcentaje = 0;

  public $data_calculate = [];

  public function __construct($item)
  {
    $this->item = $item;
    $this->precio = $item->DetPrec;
    $this->DetCant = $item->DetCant;
    $this->base_imponible = $item->DetBase;
    $this->isc_porc = $item->DetISCP;

    $this->is_gravado = $item->isGravada();
    
    $this->setIGV();
  }


  public function getCalculos()
  {
    return $this->data_calculate;
  }

  public function setValues(
    $precio,
    $cantidad,
    bool $incluye_igv,
    $base_imponible,
    $descuento_porcentaje = 0,
    bool $is_bolsa = false,
    $isc_porc = 0,
    $factor = 1,
    $tipo_cambio = 1,
    $is_sol = true
  ) {
    $this->precio = $precio;
    $this->cantidad = $cantidad;
    $this->incluye_igv = $incluye_igv;
    $this->base_imponible = $base_imponible;
    $this->is_gravado =  $base_imponible == Venta::GRAVADA;
    $this->is_bolsa = $is_bolsa;
    $this->isc_porc = $isc_porc;
    $this->descuento_porcentaje = $descuento_porcentaje;
    $this->has_value = true;
    $this->factor = $factor;
    $this->tipo_cambio = $tipo_cambio;
    $this->is_sol = $is_sol;
  }

  public function setIGV()
  {
    $math = math();

    $this->igv = $this->item->DetIGVV;

    $this->igv_bace_cero = $math->porcFactor($this->igv);
    $this->igv_bace_uno = $math->factorDivider($this->igv);
    $this->bolsa_unidad = $this->item->icbper_unit;
  }

  public function getRound($value)
  {
    return math()->decimal($this->rounded === false ? $value : round($value, $this->rounded), 2);
  }

  public function calculate()
  {
    $descuento = 0;


    if ($this->is_gravado) {
      # Calcular valores iniciales
      if ($this->incluye_igv) {
        $precio_unitario = $this->precio;
        $valor_unitario =  $this->precio / $this->igv_bace_uno;
      }
      # 
      else {
        $precio_unitario = $this->precio * $this->igv_bace_uno;
        $valor_unitario = $this->precio;
      }
    } 
    else {
      $precio_unitario = $this->precio;
      $valor_unitario = $this->precio;
    }

    $valor_noonorosa = $this->base_imponible == Venta::GRATUITA ? $this->precio : 0;
    
    if ($this->isc_porc) {
      $valor_unitario =  $valor_unitario / math()->factorDivider($this->isc_porc);
    }

    $valor_venta_bruto = $valor_unitario * $this->cantidad;    

    # Descuento
    if ($this->descuento_porcentaje) {
      $descuento = ($valor_venta_bruto / 100) * $this->descuento_porcentaje;
    }

    $valor_venta_por_item = $valor_venta_bruto - $descuento;

    #Calcular el ISC
    $isc_total = $this->isc_porc ? ($valor_venta_por_item *  math()->porcFactor($this->isc_porc)) : 0;

    $valor_venta_por_item_igv = $valor_venta_por_item + $isc_total;

    # Calcular IGV
    $igv_total = $this->is_gravado ? $valor_venta_por_item_igv * $this->igv_bace_cero  : 0;

    #Calcular bolsa
    $bolsa = $this->is_bolsa ? $this->bolsa_unidad * $this->cantidad : 0;

    $total = $valor_venta_por_item + $isc_total + $igv_total + $bolsa;

    $impuestos_totales = $isc_total + $igv_total + $bolsa;

    return $this->data_calculate = [
      'base_imponible' => $this->base_imponible,
      'cantidad' => $this->cantidad,
      'precio' => $this->precio,
      'factor' => $this->factor,

      'precio_unitario' => $precio_unitario,
      'valor_unitario' => $valor_unitario,

      'valor_noonorosa' => $valor_noonorosa,
      'valor_venta_bruto' => $this->getRound($valor_venta_bruto),
      'descuento' => $this->getRound($descuento),
      'valor_venta_por_item' => $this->getRound($valor_venta_por_item),
      'valor_venta_por_item_igv' => $valor_venta_por_item_igv,
      'igv_total' => $this->getRound($igv_total),
      'igv_unitario' =>  $this->is_gravado ?  $this->igv : 0,
      'isc' => $isc_total,
      'isc_porc' => $this->isc_porc,
      'impuestos_totales' => $impuestos_totales,
      'bolsa' => $bolsa,
      'bolsa_unit' =>  $this->is_bolsa ? $this->bolsa_unidad : 0,
      'total' => $this->getRound($total),
    ];
  }
}
