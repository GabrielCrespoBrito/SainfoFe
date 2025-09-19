<?php

namespace App\Jobs\ImportFromXmls;

use App\ClienteProveedor;

abstract class CreateFromDataAbstract
{
  public $data;
  public $empresa;
  public $empresaId;
  public $cacheTemp;

  public function __construct( $data, $empresa, &$cacheTemp ){ 
    $this->data = $data;
    $this->empresa = $empresa;
    $this->empresaId = $empresa->empcodi;
    $this->cacheTemp = $cacheTemp;
  }
  
  public abstract function handle();
}


