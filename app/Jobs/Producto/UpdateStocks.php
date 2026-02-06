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
    $stockName = 'prosto' . Producto::getNumStockFromLocCodi($loccodi);
    foreach ($request->items as $procodi) {
      $productoStocks = Producto::updateStock2($procodi);
      $data[$procodi] = $productoStocks[$stockName];
    }
    return $data;
  }
}
