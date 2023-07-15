<?php

namespace App\Models\Traits;

/**
 * Interaccionar con el inventario
 * 
 */
trait InventaryTrait
{
  /**
   * Obtener cantidad real dependiendo de la unidad utilizada
   * 
   * @return float
   */
  public function getRealQuantity()
  {
    return $this->unidad->getRealQuantity($this->{$this->getRealFieldName('DetCant')} );
  }

  public function reduceInventary($stock = 1, $cantidad = null)
  {
    $cantidad = $cantidad ?? $this->getRealQuantity();

    $this->producto->reduceInventary( $cantidad, $stock );
  }
  
  public function agregateInventary($stock = 1, $cantidad = null)
  {
    $cantidad = $cantidad ?? $this->getRealQuantity();

    optional($this->producto)->agregateInventary( $cantidad, $stock);
  }

  /**
   * Agregar o reducir del inventario
   * @param bool $agregate
   * 
   */
  public function actionInventary( bool $agregate, $stock = 1)
  {
    $agregate ? $this->agregateInventary($stock) : $this->reduceInventary($stock);
  }

  /**
   * Agregar o reducir del inventario
   * @param bool $agregate
   * @return void
   */
  public function actionInventaryByCantidadItem( bool $agregate, $stock = 1)
  {
    $cantidad = $this->Detcant * $this->DetFact;

    $agregate ? $this->agregateInventary($stock, $cantidad) : $this->reduceInventary($stock, $cantidad);
  }
}
