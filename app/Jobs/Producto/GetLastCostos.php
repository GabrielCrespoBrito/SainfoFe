<?php

namespace App\Jobs\Producto;

use App\Producto;

class GetLastCostos
{
  protected $procodi;
  protected $unidad;
  protected $unidad_costo_soles;
  protected $unidad_costo_dolar;
  protected $unidad_factor;
  protected $fecha;
  protected $local;
  protected $cantidad;
  protected $factor_venta;
  protected $ultima_compra;
  protected $incluye_igv;
  protected $noSearchUltimaCompra;
  public function __construct(
    $procodi, 
    $unidad_costo_soles, 
    $unidad_costo_dolar,
    $unidad_factor,
    $fecha, 
    $local, 
    $cantidad,
    $factor_venta,
    $incluye_igv = true,
    $noSearchUltimaCompra = false )
  {
    $this->procodi = $procodi; 
    $this->fecha = $fecha;
    $this->unidad_costo_soles = $unidad_costo_soles; 
    $this->unidad_costo_dolar = $unidad_costo_dolar;
    $this->unidad_factor = $unidad_factor;
    $this->local = $local; 
    $this->cantidad = $cantidad; 
    $this->factor_venta = $factor_venta;
    $this->incluye_igv = $incluye_igv;
    $this->ultima_compra = $noSearchUltimaCompra ? null :  $this->getUltimaCompra();
  }

  public function getUltimaCompra()
  {
    return Producto::getLastCompra($this->procodi, $this->fecha, $this->local);
  }

  public function calculate()
  {
    $igv = get_empresa()->getIgvPorc();

    
    if ( $this->ultima_compra ) {
      $costo_sol_unitario = ($this->ultima_compra->DetCSol  / $this->ultima_compra->DetCant);
      $costo_dolar_unitario = ($this->ultima_compra->DetCDol / $this->ultima_compra->DetCant);
      $factor_costo = $this->ultima_compra->Detfact;
    }

    else {
      $costo_sol_unitario = $this->incluye_igv ? ($this->unidad_costo_soles / $igv->igvBaseUno) : $this->unidad_costo_soles;
      $costo_dolar_unitario = $this->incluye_igv ?  ($this->unidad_costo_dolar / $igv->igvBaseUno ) : $this->unidad_costo_dolar;
      $factor_costo = $this->unidad_factor;
    }

    $factor_multiplicador = $this->factor_venta / $factor_costo;
    $costo_sol = $costo_sol_unitario * ($this->cantidad * $factor_multiplicador);
    $costo_dolar = $costo_dolar_unitario * ($this->cantidad * $factor_multiplicador);
    
    return (object) [
      'sol' => $costo_sol,
      'dolar' => $costo_dolar,
    ];
  }

  public function handle()
  {
    return $this->calculate();
  }

}