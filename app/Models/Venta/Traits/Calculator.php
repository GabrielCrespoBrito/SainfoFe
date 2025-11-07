<?php
namespace App\Models\Venta\Traits;

use App\Venta;
use Exception;
use Illuminate\Support\Facades\Log;

class Calculator
{
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
  public $tipo_cambio;
  public $base_imponible;
  public $is_gravado;
  public $is_bolsa;
  public $isc_porc;
  public $factor;
  public $is_sol;
  public $peso;

  public $descuento_porcentaje = 0;

  public $data_calculate = [];

  public function __construct($igvByUser = false, $igvPorc = null )
  {
    if( $igvByUser && $igvPorc == null ){
      throw new Exception("Es necesario el porcetaje de igv, cuando es igv personalizado", 1);
    }

    $this->setIGV(
      $igvByUser,
      $igvPorc
    );
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
    $is_sol = true,
    $peso = 0
    )
  {
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
    $this->peso = $peso;
  }

  public function setIGV( $igvByUser, $igvPorc ) {    
    
    if( $igvByUser ){
      $this->igv = $igvPorc;
      $this->igv_bace_cero = math()->baseCerapio($igvPorc);
      $this->igv_bace_uno = math()->baseUno($igvPorc);
    }
    else {
      $igvs = get_empresa()->getIgvPorc();
      $this->igv = $igvs->igvPorc ;
      $this->igv_bace_cero = $igvs->igvBaseCero;
      $this->igv_bace_uno = $igvs->igvBaseUno;    
    }

    $this->bolsa_unidad = config('app.parametros.bolsa');

  }

  public function getRound($value)
  {
    return math()->decimal($this->rounded === false ? $value : round( $value, $this->rounded ) ,2);
  }

  public function calculate()
  {
    if(!$this->has_value) {
      throw new Exception("No se ha establecidos los valores todavia", 1);
    }

    $isGratuito = $this->base_imponible == Venta::GRATUITA;
    
    $descuento = 0;

    if( $this->is_gravado ){
      # Calcular valores iniciales
      if( $this->incluye_igv ){
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
      $valor_unitario = $isGratuito ? 0 :  $this->precio;
    }

    # Calculos Costos
    $factorItem = $this->cantidad;    
    $costo = $valor_unitario * $factorItem; 
    $costo_soles = $this->is_sol ? $costo : $costo * $this->tipo_cambio;
    $costo_dolares = $this->is_sol ? $costo / $this->tipo_cambio : $costo;
    
    $valor_noonorosa = $isGratuito ? $this->precio : 0;
    
    if( $this->isc_porc ){
      $valor_unitario =  $valor_unitario;
      $precio_unitario =  $precio_unitario * math()->factorDivider($this->isc_porc);
    }

    $valor_venta_bruto =  ($isGratuito ? $valor_noonorosa : $valor_unitario) * $this->cantidad;

    # Descuento
    if( $this->descuento_porcentaje ){
      $descuento = ($valor_venta_bruto / 100) * $this->descuento_porcentaje;
      $precio_unitario -= (($precio_unitario / 100) * $this->descuento_porcentaje);
    }

    $valor_venta_por_item = $valor_venta_bruto - $descuento;

    #Calcular el ISC
    $isc_total = $this->isc_porc ? ($valor_venta_por_item *  math()->porcFactor( (float) $this->isc_porc) ) : 0;

    $valor_venta_por_item_igv = $valor_venta_por_item + $isc_total;

    # Calcular IGV
    $igv_total = $this->is_gravado ? $valor_venta_por_item_igv * $this->igv_bace_cero  : 0;

    #Calcular bolsa
    $bolsa = $this->is_bolsa ? $this->bolsa_unidad * $this->cantidad : 0;

    $total = $valor_venta_por_item + $isc_total + $igv_total + $bolsa;
    
    $impuestos_totales = $isc_total + $igv_total + $bolsa;

    $peso_total = $this->peso * $this->cantidad;
    
    $r =  $this->data_calculate = [
      'base_imponible' => $this->base_imponible,
      'cantidad' => $this->cantidad,
      'precio' => $this->precio,
      'factor' => $this->factor,
      'costo_dolares' => $costo_dolares,
      'costo_soles' => $costo_soles,
      'precio_unitario' => $precio_unitario,
      'valor_unitario' => $valor_unitario,    
      'valor_noonorosa' => $valor_noonorosa,
      'valor_venta_bruto' => $valor_venta_bruto,
      'descuento_porcentaje' => $this->descuento_porcentaje,
      'descuento' => $descuento,
      'valor_venta_por_item' => $valor_venta_por_item,
      'valor_venta_por_item_igv' => $valor_venta_por_item_igv,
      'igv_total' => $igv_total,
      'igv_unitario' => $this->is_gravado ? $this->igv : 0,
      'isc' => $isc_total,
      'peso' => $peso_total,
      'isc_porc' => $this->isc_porc,
      'impuestos_totales' => $impuestos_totales,
      'bolsa' => $bolsa,
      'bolsa_unit' =>  $this->is_bolsa ? $this->bolsa_unidad : 0, 
      'total' => $total,
    ]; 
    return $r;
  }
}