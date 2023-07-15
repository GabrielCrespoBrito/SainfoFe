<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreDataTomaInventario
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
      if( $event->is_store == false ){
        $event->toma_inventario->detalles()->delete();
      }

      foreach( $event->items as $item )
      {      
        unset( $item['is_ingreso'] );
        unset( $item['ProPUVS'] );
        $event->toma_inventario->detalles()->create($item);
      }

      if( $event->toma_inventario->isCerrada()) {
        $event->toma_inventario->createGuias();
      }

    }
  }
