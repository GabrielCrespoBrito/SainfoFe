<?php

namespace App\Listeners;

use App\Jobs\Compra\UpdatedProductUltimosCostos;
use App\Jobs\CreatedCompraItems;

class CompraUpdatedListener
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  object  $event
   * @return void
   */
  public function handle($event)
  {
    $compra = $event->compra;

    $items_original = $compra->items;

    # Borrar items anteriores
    $compra->deleteItems();

    # Crear items 
    CreatedCompraItems::dispatchNow($compra, $event->items);

    $compra->refresh();

    // Comprate
    $job = new UpdatedProductUltimosCostos($items_original, $compra);
    $job->handle();
  }
}
