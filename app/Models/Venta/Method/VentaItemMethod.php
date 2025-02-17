<?php

namespace App\Models\Venta\Method;

use App\Jobs\UpdateCosto;
use App\Jobs\VentaItem\RecalculateTotal;
use App\Moneda;
use App\Venta;
use App\TipoIgv;

trait VentaItemMethod
{
  public function itemsPedido($pedido)
  {
    return $pedido->items->where('UniCodi', $this->UniCodi)->first();
  }

  public function realQuantityProduct($quantity)
  {
    return optional($this->unidad)->getRealQuantity($quantity);
  }

  public function reduceAlmacen()
  {
    $this->producto->reduceInventary($this->realQuantityProduct($this->DetCant));
  }

  public function reduceReserved($pedido)
  {
    $itemsPedido = $this->itemsPedido($pedido);
    $cantProducto = $itemsPedido->unidad->getRealQuantity($itemsPedido->DetCant);

    $diferent = $this->realQuantityProduct($this->DetCant)  - $cantProducto;

    $this->producto->reduceReserved($cantProducto);

    if ($diferent) {
      $this->producto->reduceInventary($this->realQuantityProduct($diferent));
    }
  }

  public function agregateAlmacen()
  {
    optional($this->producto)->agregateInventary($this->realQuantityProduct($this->DetCant));
  }

  public function agregateReserved($pedido)
  {
    $itemsPedido = $this->itemsPedido($pedido);
    $cantProducto = $itemsPedido->unidad->getRealQuantity($itemsPedido->DetCant);

    $this->producto->reduceInventary($cantProducto);
    $this->producto->agregateReserved($cantProducto);

    $diferent = $this->realQuantityProduct($this->DetCant) - $cantProducto;

    if ((bool) $diferent) {
      $this->producto->agregateInventary($this->realQuantityProduct($diferent));
    }
  }

  public function isGravada()
  {
    return $this->DetBase == Venta::GRAVADA;
  }

  public function isGratuita()
  {
    return $this->DetBase == Venta::GRATUITA;
  }

  public function isGratuitaGravada()
  {
    return  in_array($this->TipoIGV, TipoIgv::GRATUITAS_GRAVADAS);
  }

  public function isExonerada()
  {
    return $this->DetBase == Venta::EXONERADA;
  }

  public function isInafecta()
  {
    return $this->DetBase == Venta::INAFECTA;
  }

  /**
   * Si el item tiene descuento
   * 
   * @return Bool
   */
  public function hasDescuento(): Bool
  {
    return (bool) ((float) $this->DetDcto);
  }

  /**
   * Si el item tiene ISC
   * 
   * @return Bool
   */
  public function hasISC(): Bool
  {
    return (bool)  $this->DetISCP;
  }

  /**
   * Si se le aplica igv
   * 
   * @return Bool
   */
  public function hasIGV(): Bool
  {
    return (bool) (float) $this->DetIGVV;
  }

  /**
   * Devolver el tipo de igv correcto
   *
   * @return void
   */
  public function fillTipoIGV($tipoIGV = null)
  {
    switch ($this->DetBase) {
      case Venta::GRAVADA:
        $this->TipoIGV = TipoIgv::DEFAULT_GRAVADA;
        break;
      case Venta::INAFECTA:
        $this->TipoIGV = TipoIgv::DEFAULT_INAFECTA;
        break;
      case Venta::EXONERADA:
        $this->TipoIGV = TipoIgv::DEFAULT_EXONERADA;
        break;
      case Venta::GRATUITA:
        $this->TipoIGV = $tipoIGV;
        break;
    }
  }


  /**
   *  Poner el tipo de igv correcto para el item dependiendo del tipo de impuesto
   * 
   * @return void
   * 
   */
  public function resolverTipoIGV()
  {
    $tipo = null;

    switch ($this->DetBase) {
      case Venta::GRAVADA:
        $tipo = TipoIgv::DEFAULT_GRAVADA;
        break;
      case Venta::INAFECTA:
        $tipo = TipoIgv::DEFAULT_INAFECTA;
        break;
      case Venta::EXONERADA:
        $tipo = TipoIgv::DEFAULT_EXONERADA;
        break;
      case Venta::GRATUITA:
        $tipo = $this->TipoIGV;
        break;
    }

    $this->update(['tipoIGV' => $tipo]);
  }

  /**
   * Poner el porcentaje real de igv dependiendo del tipo de igv del documento
   * 
   * @return void
   */


  public function setPorcentajeIGV()
  {
    $igvPorc = $this->tipoigv->isGravada() ? get_empresa()->igvPorcentaje : 0;

    $this->update(['DetIGVV' => $igvPorc]);
  }

  /**
   * Poner el porcentaje real de igv dependiendo del tipo de igv del documento
   * 
   * @return void
   */


  public function fillPorcentajeIGV()
  {
    $igvPorc = TipoIgv::getIfTipoGravada($this->DetBase) ? get_empresa()->igvPorcentaje : 0;
    $this->fill(['DetIGVV' => $igvPorc]);
  }

  /**
   * Si este item se le aplica el porcentaje por bolsa
   *
   * @return bool
   */
  public function hasICBPER()
  {
    return (bool) ((float) $this->icbper_value);
  }


  /**
   * Si este item se le aplica el porcentaje por bolsa
   *
   * @return bool
   */
  public function getBolsaUnit()
  {
    // @TODO
    return "0.20";
  }

  /**
   * Si este item se le aplica el porcentaje por bolsa
   *
   * @return bool
   */
  public function getBolsaTotal()
  {
    return $this->icbper_value;
  }

  /**
   * 
   * @return array
   */
  public function makeCalculates($data)
  {
    /**
     * ---------------------------------------------------------------
     * Precio Unitario:  Precio por unidad que no incluye el igv
     * Valor Unitario:  Valor por unidad que incluye el igv
     * ---------------------------------------------------------------
     */

    $unitario = 10;
    $precio_unitario = 10;
    $cantidad = 5;
    $incluye_igv = true;
    $descuento = 0;
    $isc_porc = 20;

    //
    $afectacion_igv = 18;
    $igv_factor = 1.18;
    $igv_mul = 0.18;

    // 16.94
    $precio_unitario = $incluye_igv ?  ($unitario / $igv_factor) : $unitario;
    $valor_unitario = $incluye_igv ? $unitario : $unitario + ($unitario * $igv_mul);

    // --------------- Inafecto / Suministros ---------------

    return [
      'precio_unitario' => $precio_unitario,
      'valor_untario' => $valor_unitario,
      'cantidad' => $cantidad,
      'cantidad' => $cantidad,
      'cantidad' => $cantidad,
      'igv' => $afectacion_igv,
      'igv_unidad' => 0,
    ];
  }

  public function getCostosPorVendedor()
  {
    if ($this->DetPorcVend) {
      return (object) [
        'soles' => $this->DetPorcVenSol,
        'dolar' => $this->DetPorcVenDol
      ];
    }


    if (is_null($this->producto->porc_com_vend)) {
      return (object) [
        'soles' => 0,
        'dolar' => 0,
      ];
    }

    return (object) [
      'soles' => math()->porc($this->producto->porc_com_vend, $this->DetVSol),
      'dolar' => math()->porc($this->producto->porc_com_vend, $this->DetVDol),
    ];
  }


  /**
   * Obtener la utilidad del item
   * 
   * @return array
   */
  public function getDataUtilidadProducto($convert = true, $descontarPorcVendedor = false)
  {
    if ($this->parent->isAnulada() || $this->parent->isRechazado()) {
      return [
        'venta_soles' => 0,
        'venta_dolar' => 0,
        'costo_soles' => 0,
        'costo_dolar' => 0,
        'costo_soles_por_vendedor' => 0,
        'costo_dolar_por_vendedor' => 0,
        'utilidad_soles' => 0,
        'utilidad_dolar' => 0,
      ];
    }

    $convertToNegative = $convert ? $this->parent->isNotaCredito() : false;


    // Vendedor Costo Por defecto
    $vendedorCosto = (object) [
      'soles' => 0,
      'dolar' => 0,
    ];

    if ($descontarPorcVendedor) {
      $vendedorCosto = $this->getCostosPorVendedor();
    }

    $v = [
      'id' => $this->VtaOper,
      'venta_soles' => convertNegativeIfTrue($this->DetVSol, $convertToNegative),
      'venta_dolar' => convertNegativeIfTrue($this->DetVDol, $convertToNegative),
      'costo_soles' => convertNegativeIfTrue($this->DetCSol, $convertToNegative),
      'costo_dolar' => convertNegativeIfTrue($this->DetCDol, $convertToNegative),
      'costo_soles_por_vendedor' => $vendedorCosto->soles,
      'costo_dolar_por_vendedor' => $vendedorCosto->dolar,
      'utilidad_soles' => convertNegativeIfTrue($this->DetVSol - ($this->DetCSol + $vendedorCosto->soles), $convertToNegative),
      'utilidad_dolar' => convertNegativeIfTrue($this->DetVDol - ($this->DetCDol + $vendedorCosto->dolar), $convertToNegative),
    ];

    return $v;
  }




  /**
   * Actualizar costos
   * 
   * @return void
   */
  public function updateCosto()
  {
    new UpdateCosto($this);
  }

  /**
   * Obtener la moneda
   * 
   * @return numeric
   */
  public function isSol()
  {
    return $this->parent->isSol();
  }

  /**
   * Obtener el tipo de cambio moneda
   * 
   */
  public function getTipoCambio()
  {
    return $this->parent->tipo_cambio;
  }

  /**
   * Recalcular totales
   * 
   */
  public function recalculateTotals($calculatePrecios = true)
  {
    RecalculateTotal::dispatchNow($this, $calculatePrecios);
  }

  public function completeDescripcion()
  {
    return str_replace(array("\r", "\n"), '', $this->DetNomb . ' ' .  $this->DetDeta);
  }


  /**
   * 
   * 
   */
  public function hasPlaca()
  {
    return (bool) $this->detfven;
  }

  /**
   * 
   * 
   */
  public function getPlaca()
  {
    return $this->detfven;
  }

  public function hasNameAnticipo()
  {
    return strpos(strtoupper($this->DetNomb), "ANTICIPO:") !== false;
  }

  public function divideDay()
  {
    /**
     *
     */

    return [
      'block_1' => ['5:30', '8:30'],
      'block_2' => ['8:30', '6:40'],
      'block_2' => ['6:30',  '10:00'],
    ];
  }


  public function isNotaCredito()
  {
    return $this->parent->isNotaCredito();
  }
}
