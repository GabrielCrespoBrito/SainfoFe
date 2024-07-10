<?php

namespace App\Observers;

use App\Producto;

class ProductoObserver
{
  /**
   * Handle the producto "updated" event.
   *
   * @param  \App\Producto  $producto
   * @return void
   */
  public function updating(Producto $producto)
  {
    $producto->User_EModi = gethostname();
    $producto->User_Modi = optional(auth()->user())->usulogi;
  }
}
