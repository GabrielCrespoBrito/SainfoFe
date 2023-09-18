<?php

namespace App\Jobs\Empresa;

use App\GuiaSalida;
use Illuminate\Support\Facades\Log;

class sendGuiasPendientes
{
  public $empresa;

  public function __construct($empresa)
  {
    $this->empresa = $empresa;
  }

  public function handle()
  {
    empresa_bd_tenant($this->empresa->empcodi);
    // 
    $guias = $this->empresa->guias
      ->where('GuiEFor', GuiaSalida::CON_FORMATO)
      ->where('EntSal', GuiaSalida::SALIDA)
      ->where('fe_rpta', GuiaSalida::ESTADO_SUNAT_PENDIENTE);

    foreach ($guias as $guia) {
      try {
        $guia->sendApi();
      } catch (\Throwable $th) {
        Log::error(sprintf('@ERROR GUIA-PENDIENTE %s - %s' , $guia->GuiOper, $th->getMessage()));
      }
    }
  }
}
