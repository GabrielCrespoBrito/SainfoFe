<?php

namespace App\Models\Unidad\Method;

use App\Jobs\Producto\GetLastCostos;

trait UnidadMethod
{
  public function isPrincipal()
  {
    return $this->producto()->unidadPrincipal()->Unicodi == $this->Unicodi;
  }

  public function getFactor()
  {
    return $this->UniEnte / $this->UniMedi;
  }

  public function getRealQuantity($quantity)
  {
    return $this->getFactor() * $quantity;
  }

  public static function calculatePrecioCostoByNewTipoCambio($tipo_cambio, $costo_dolares, $precio_dolares, $precio_min_dolares  )
  {
    $precio_soles = $precio_dolares * $tipo_cambio;
    $precio_min_soles = $precio_min_dolares * $tipo_cambio;
    $costo_soles = $costo_dolares * $tipo_cambio;
    $precio_min_soles = $precio_min_soles * $tipo_cambio;
    
    return (object) [
      'precio_soles' => $precio_soles,
      'precio_min_soles' => $precio_min_soles,
      'costo_soles' => $costo_soles,
    ];
  }

  public static function calculateValores($tipo, $campo, $value, $costo_dolares, $costo_soles, $precio_dolares, $precio_soles, $margen)
  {
    $math = math();

    // Calculo del costo
    if ($campo == 'costo') {
      $value = $tipo ? $value : -$value;
      $costo_dolares = $math->porcAndSum($value, $costo_dolares);
      $costo_soles = $math->porcAndSum($value, $costo_soles);
      $margen = $margen;
      $precio_dolares = $math->porcAndSum($value, $precio_dolares);
      $precio_soles = $math->porcAndSum($value, $precio_soles);
    }

    // Calculo del margen
    else {
      $value = $tipo ? $value : -$value;
      $costo_dolares = $costo_dolares;
      $costo_soles = $costo_soles;
      $precio_dolares = $math->porcAndSum($value, $precio_dolares);
      $precio_soles = $math->porcAndSum($value, $precio_soles);
      $margen += $value;
    }

    return (object) [
      'costo_dolares' => $costo_dolares,
      'costo_soles' => $costo_soles,
      'margen' => $margen,
      'precio_dolares' => $precio_dolares,
      'precio_soles' => $precio_soles,
    ];
  }


  public static function calculatePrecMinValores($tipo, $value, $precio_dolares, $precio_soles)
  {
    $math = math();
    $value = $tipo ? $value : -$value;
    $precio_calc_dolares = $math->porcAndSum($value, $precio_dolares);
    $precio_calc_soles = $math->porcAndSum($value, $precio_soles);

    return (object) [
      'precio_dolares' => $precio_calc_dolares,
      'precio_soles' => $precio_calc_soles,
    ];
  }



  public function updateByTipoCambio($tipo_cambio)
  {
    $result = self::calculatePrecioCostoByNewTipoCambio($tipo_cambio, $this->UniPUCD, $this->UNIPUVD, $this->UniPMVD );

    $this->update([
      'UNIPUVS' => $result->precio_soles,
      'UniPUCS' => $result->costo_soles,
    ]);
  }

  public function updateProductoPrices()
  {
    $producto = $this->producto();

    if ($producto->unidadPrincipal()->Unicodi == $this->Unicodi) {
      $producto->update([
        'unpcodi' => $this->UniAbre,
        'ProPUCD' => $this->UniPUCD,
        'ProPUCS' => $this->UniPUCS,
        'ProMarg' => $this->UniMarg,
        'ProPUVD' => $this->UNIPUVD,
        'ProPUVS' => $this->UNIPUVS,
        'ProPMVD' => $this->UNIPMVD,
        'ProPMVS' => $this->UNIPMVS,
        'ProPeso' => $this->UniPeso,
        'porc_com_vend' => $this->porc_com_vend,
      ]);
    }
  }

  public function updateCosto($costo_soles, $costo_dolares, $update_parent_producto = false)
  {
    $producto = $this->producto();
    $incluye_igv = $producto->incluye_igv;
    $igv = get_igv();
    
    $costo_soles =  $incluye_igv ? math()->porcAndSum($igv, $costo_soles) : $costo_soles;
    $costo_dolares =  $incluye_igv ? math()->porcAndSum($igv, $costo_dolares) : $costo_dolares;
    
    $this->update([
      'UniPUCS' => $costo_soles,
      'UniPUCD' => $costo_dolares,
      'UNIPUVS' => $precio_venta_soles = math()->porcAndSum($this->UniMarg, $costo_soles),
      'UNIPUVD' => $precio_venta_dolar = math()->porcAndSum($this->UniMarg, $costo_dolares),
    ]);

    if ($update_parent_producto) {
      if ($this->isPrincipal()) {
        $producto->update([
          'ProPUCS' => $costo_soles,
          'ProPUCD' => $costo_dolares,
          'ProPUVS' => $precio_venta_soles,
          'ProPUVD' => $precio_venta_dolar,
        ]);
      }
    }
  }


  /**
   * Calcular el costo de esta unidad
   * 
   * @return object
   */
  public function getCostos($procodi, $fecha, $local, $cantidad, $factor_venta, $incluye_igv = true, $noSearchUltimaCompra = false)
  {
    return (new GetLastCostos(
      $procodi,
      $this->UniPUCS,
      $this->UniPUCD,
      $this->getFactor(),
      $fecha,
      $local,
      $cantidad,
      $factor_venta,
      $incluye_igv, 
      $noSearchUltimaCompra
    ))->handle();
  }
}
