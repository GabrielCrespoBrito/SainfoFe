<?php

namespace App\Models\Venta\Traits;

use App\Venta;
use Exception;

class CalculatorTotal
{
  public $totals;
  public $totales_items = [];
  public $descuento_global_porc = 0;
  public $detraccion_porc = 0;
  public $percepcion_porc = 0;
  public $anticipo_value = 0;
  public $retencion_porc = 0;
  public $anticipo_igv = 0;
  
  public function __construct( $totales_items = [] )
  {
    $this->totales_items = $totales_items;
  }

  public function hasData()
  {
    return (bool) $this->totals;
  }

  public function setParameters( 
    $descuento_global_porc , 
    $anticipo_value =  0,
    $detraccion_porc = 0,
    $percepcion_porc = 0,
    $retencion_porc =  0,
    $anticipo_igv =  0
    ){
    $this->descuento_global_porc = $descuento_global_porc;
    $this->anticipo_value = $anticipo_value; 
    $this->detraccion_porc = $detraccion_porc;
    $this->percepcion_porc = $percepcion_porc;  
    $this->retencion_porc = $retencion_porc;
    $this->anticipo_igv = $anticipo_igv;  
  }

  public function registerItemTotal($total_item)
  {
    array_push( $this->totales_items , $total_item );
    return $this;
  }

  public function calculateTotales()
  {
    if (!count($this->totales_items)) {
      throw new Exception("Exception Dont exists totals attributes to calculo total", 1);
    }

    $hasDescuento = (bool) $descuento_global_porc =  $this->descuento_global_porc;
    $totales_items = collect($this->totales_items);
    $total_cantidad  = $totales_items->sum('cantidad');
    $total_peso = $totales_items->sum('peso');    
    $total_valor_venta = $totales_items->where('base_imponible', '!=', Venta::GRATUITA)->sum('valor_venta_por_item');
    $total_valor_bruto = $totales_items->where('base_imponible', '!=', Venta::GRATUITA)->sum('valor_venta_bruto');

    # Descuento global
    $descuento_global =  $hasDescuento ? (($total_valor_venta / 100) * $descuento_global_porc) : 0;
    $descuento_total = $totales_items->sum('descuento') + $descuento_global;
    
    # IGV    
    $igv = $totales_items->sum('igv_total');
    $igv = $hasDescuento ? $igv - (($igv / 100) * $descuento_global_porc) : $igv;
    $igv -= $this->anticipo_igv;

    # ISC
    $isc = $totales_items->sum('isc');
    $isc = $hasDescuento ?  $isc - ($isc / 100 * $descuento_global_porc) : $isc;
    $total_base_isc = 0;

    if( $isc ){
      $total_base_isc = $totales_items->where('isc', '>', 0)->sum('valor_venta_por_item');
    }

    # Icbper
    $icbper = $totales_items->sum('icbper');

    # Valor venta_por_item_igv
    $valor_venta_por_item_igv = $totales_items->sum('valor_venta_por_item_igv');

    # Total Gravada
    $total_gravadas = $totales_items->where('base_imponible', Venta::GRAVADA)->sum('valor_venta_por_item');
    $total_gravadas = $hasDescuento ?  $total_gravadas - ($total_gravadas / 100 * $descuento_global_porc) : $total_gravadas;
    $total_gravadas -= $this->anticipo_value; 

    # Total Exonerada
    $total_exonerada = $totales_items->where('base_imponible', Venta::EXONERADA)->sum('valor_venta_por_item');
    $total_exonerada = $hasDescuento ?  $total_exonerada -($total_exonerada / 100 * $descuento_global_porc) : $total_exonerada;

    # Total Inafecta
    $total_inafecta = $totales_items->where('base_imponible', Venta::INAFECTA)->sum('valor_venta_por_item');
    $total_inafecta = $hasDescuento ?  $total_inafecta -($total_inafecta / 100 * $descuento_global_porc) : $total_inafecta;

    # Total Gratuita
    // $total_gratuita = $totales_items->where('base_imponible', Venta::GRATUITA)->sum('valor_noonorosa');
    $total_gratuita = $totales_items->where('base_imponible', Venta::GRATUITA)->sum('total');

    # Importe total
    $total_importe = ($total_gravadas + $total_exonerada + $total_inafecta + $igv + $isc + $icbper);

    # Percepciòn
    // $percepcion = $this->percepcion_porc ? $total_importe * math()->porcFactor($this->percepcion_porc) : 0;
    $percepcion = 0;
    $total_base_percepcion = 0;
    if( $this->percepcion_porc){
      $total_base_percepcion = $total_gravadas + $total_exonerada + $total_inafecta;
      $percepcion =  $total_base_percepcion * math()->porcFactor($this->percepcion_porc);
    }

    # Total cobrando
    // $total_cobrado = ($total_importe + $percepcion);
    $total_cobrado =  $total_importe;
    //  ($total_importe + $percepcion);

    # Retencion
    $retencion = $this->retencion_porc ? $total_cobrado * math()->porcFactor($this->retencion_porc) : 0;

    # Detraccion
    $detraccion = $this->detraccion_porc ? $total_cobrado * math()->porcFactor($this->detraccion_porc) : 0;

    $this->totals = (object) [
      'total_cantidad' => $total_cantidad,
      'total_valor_bruto_venta' => $total_valor_bruto,
      'total_valor_venta' => $total_valor_venta,
      'total_base_isc' => $total_base_isc,
      'total_base_percepcion' => $total_base_percepcion,
      
      'descuento_global_valor' => $descuento_global,
      'descuento_global'  => $descuento_global,
      'descuento_total'   =>  $descuento_total,
      'valor_venta_por_item_igv' => $valor_venta_por_item_igv,
      'icbper' => $icbper,
      'anticipo' => $this->anticipo_value,
      // ---------------------------------------------------------------------------------------------------------------------------------------------------
      'percepcion_porc' => $this->percepcion_porc,
      'percepcion' => $percepcion,
      // --------------------------------      
      'detraccion_porc' => $this->detraccion_porc,
      'detraccion' => $detraccion,
      // --------------------------------      
      'retencion_porc' => $this->retencion_porc,
      'retencion' => $retencion,
      // --------------------------------      
      'igv' =>  $igv,
      'isc' => $isc,
      'impuestos_totales' => ($igv + $isc + $icbper),
      'total_gravadas' => $total_gravadas,
      'total_exonerada' => $total_exonerada,
      'total_inafecta' => $total_inafecta,
      'total_gratuita'       => $total_gratuita,
      'total_peso'       => $total_peso,
      'total_importe' => math()->decimal($total_importe,2),
      'total_cobrado' => math()->decimal($total_cobrado, 2),
    ];
  }

  public function getTotal()
  {
    if( !$this->totals ){
      $this->calculateTotales();
    }

    return $this->totals;
  }

}