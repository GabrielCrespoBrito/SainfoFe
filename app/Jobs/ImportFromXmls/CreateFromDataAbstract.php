<?php

namespace App\Jobs\ImportFromXmls;

abstract class CreateFromDataAbstract
{
  public $data;
  public $empresa;

  public function __construct($data, $empresa){
    $this->data = $data;
    $this->empresa = $empresa;
  }

  public abstract function handle();
}


