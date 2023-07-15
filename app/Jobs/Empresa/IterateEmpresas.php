<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use Illuminate\Support\Facades\Log;

class IterateEmpresas
{
  public function __construct()
  {
  }

  public function handle($callBack, $callBackError = null)
  {
    $empresas_group = Empresa::all()->chunk(50);
    foreach ($empresas_group as $empresas) {
      foreach ($empresas as $empresa) {
        try {
          $callBack($empresa);
        } catch (\Throwable $th) {
          $callBackError ? $callBackError($th) : Log::info("@ERROR " . $th->getMessage());
        }
      }
    }      
  }
}


