<?php

namespace App\Jobs\Producto;

use App\Producto;

class UpdateStocks
{

  public $request;

  public function __construct($request)
  {
    $this->request = $request;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $data = [];
    $request = $this->request;
    $loccodi = $request->loccodi;
    foreach ($request->items as $procodi) {
      $producto = Producto::findByProCodi($procodi);
      $producto->reProcessInventario($loccodi);
      $data[$procodi] = $producto->getStockFromLocCodi($loccodi); 
    }
    return $data;
  }
}
