<?php

namespace App\Listeners;

use App\Events\CompraCreated;
use App\Jobs\CreatedCompraItems;

class CompraCreatedListener
{
    public function __construct()
    {
    }

    public function handle(CompraCreated $event)
    {
      # Registrar Detalle 
      CreatedCompraItems::dispatch( $event->compra, $event->items );
      $event->compra->setProductsUltimoCosto();
      $event->compra->updateOrdenCompra();
    }
}
