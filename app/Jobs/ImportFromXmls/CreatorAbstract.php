<?php

namespace App\Jobs\ImportFromXmls;

use App\Util\ResultTrait;

abstract class CreatorAbstract
{
  use ResultTrait;
  public $xmlContent;
  public $empresa;
  public $data;

  public function __construct($xmlContent, $empresa)
  {
    $this->xmlContent = $xmlContent;
    $this->empresa = $empresa;
  }

  public abstract function generateData();
  public abstract function saveDataModel();
  
  public function handle()
  {
    try {
      $this->generateData();
      $this->saveDataModel();
      $this->setSuccess($this->data);
    } catch (\Throwable $th) {
      $this->setError($th->getMessage());
    }
    return $this;
  }
}