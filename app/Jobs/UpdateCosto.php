<?php

namespace App\Jobs;

use App\VentaItem;

class UpdateCosto
{
  protected $item;

  public function __construct( VentaItem $item  )
  {
    $this->item = $item;
    $this->handle();
  }

  public function handle()
  {
    $item = $this->item;

    $isSol = $item->isSol();
    $tipo_cambio = $item->getTipoCambio();
    $valor_unitario = $item->getValorOrPrecioUnitario()->valor;    
    $factor = $item->unidad->getFactor();
    $factorItem = $factor * $item->cantidad;
    $costo = $valor_unitario * $factorItem;
    $costo_sol =  $isSol ? $costo : $costo * $tipo_cambio;
    $costo_dolar = $isSol ? $costo / $tipo_cambio : $costo;

    $item->update([
      'Detfact' => (string) $factor,
      'DetCSol' => $costo_sol,
      'DetCDol' => $costo_dolar,
    ]);

  }


}