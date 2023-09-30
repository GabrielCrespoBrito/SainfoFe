<?php

namespace App\Jobs\MedioPago;

use App\Models\MedioPago\MedioPago;
use App\Repositories\MedioPagoRepository;

class SyncWithMainTable
{
  public $empresa;
  public $tipos_pagos;

  public function __construct($empresa , $tipos_pagos )
  {
    $this->empresa = $empresa;
    $this->tipos_pagos = $tipos_pagos;
    empresa_bd_tenant($this->empresa->empcodi);
  }

  public function handle()
  {
    foreach( $this->tipos_pagos as $tipo_pago ){
      $tipo_pago = MedioPago::firstOrCreate(['tipo_pago' => $tipo_pago->TpgCodi ], [
        'empcodi' => $this->empresa->empcodi
      ]);
    }

    (new MedioPagoRepository( new MedioPago(), $this->empresa->empcodi ))->clearCache('all');
  }
}
