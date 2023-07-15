<?php

namespace App\Jobs\Cotizacion;

use App\Cotizacion;

class SetFacturado 
{
  public $cotizacion;
  public $vtaOper;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct( Cotizacion $cotizacion, $vtaOper = null )
  {
    $this->cotizacion = $cotizacion;
    $this->vtaOper = $vtaOper;
  }

  /**
   * Execute the jobs.
   *
   * @return void
   */
  public function handle()
  {
    
    if( $this->cotizacion->isPreventa() ){
      $this->cotizacion->load('items.producto');
      $items = $this->cotizacion->items;
      foreach ($items as $item) {
        $item->eliminateReserved();
      }
    }

    $this->cotizacion->setFacturadoState($this->vtaOper);
  }
}