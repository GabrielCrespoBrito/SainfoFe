<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use App\EmpresaOpcion;

class UpdateModulos
{
  public $empresa;
  public $data;

  public function __construct( Empresa $empresa, array $data)
  {
    $this->empresa = $empresa;
    $this->data = $data;
  }

  public function handle()
  {
    $dataUpdate = [];
    foreach(EmpresaOpcion::MODULOS as $modulo ){
      $dataUpdate[ $modulo ] = $this->data[ $modulo ] ?? 0;
    }

    $this->empresa->updateDataAdicional($dataUpdate);
    return;
  }
}
