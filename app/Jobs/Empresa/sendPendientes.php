<?php

namespace App\Jobs\Empresa;

use App\GuiaSalida;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class sendPendientes
{
  public $empresa;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($empresa)
  {
    $this->empresa = $empresa;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    empresa_bd_tenant($this->empresa->empcodi);
    $ventas = $this->empresa->ventas->where('VtaFMail', StatusCode::CODE_ERROR_0011);

    foreach ($ventas as $venta) {
      $venta->sendSunatPendiente(true, $this->empresa);
    }
  }
 
}