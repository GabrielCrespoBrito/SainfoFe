<?php

namespace App\Listeners;

use App\Jobs\Compra\UpdatedProductUltimosCostos;

class CompraDeletingListener
{
  /**
   * Handle the event.
   *
   * @param  object  $event
   * @return void
   */
  public function handle($event)
  {
    $compra = $event->compra;

    $items_original =  $compra->items;

    # Borrar items de la compra 
    $compra->deleteMany('items');

    # Borrar pagos de compra
    $compra->deleteMany('pagos', function ($pago) {
      $nota_credito = $pago->nota_credito;
      $pago->removeMovimiento();
      $pago->delete();
      optional($nota_credito)->updateDeudaByPagoNotaCredito();
    });

    # Guia de remision
    $guia = $compra->guia;

    # Deshacer movimientos con las guia, es decir, devolver el inventario
    optional($guia)->cancel(true);

    # Eliminar guia
    optional($compra->guia)->delete();

    # Borrar Final
    $compra->delete();

    $job = new UpdatedProductUltimosCostos($items_original, null);
    $job->handle();
  }
}
