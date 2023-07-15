<?php

namespace App\Jobs\Empresa;

use App\Producto;
use Illuminate\Support\Facades\DB;

class CambiarAplicacionIgvProductos
{
  public $incluir_igv;

  public function __construct($incluir_igv)
  {
    $this->incluir_igv = $incluir_igv;
  }

  public function handle()
  {    
    DB::connection('tenant')
    ->table('productos')
    ->update([
      'incluye_igv' => $this->incluir_igv
    ]);
  }
}
