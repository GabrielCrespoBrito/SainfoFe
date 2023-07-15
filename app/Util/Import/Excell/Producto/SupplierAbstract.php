<?php

namespace App\Util\Import\Excell\Producto;

abstract class SupplierAbstract
{
  protected $entidadData;
  protected $campoValue;
  public $empcodi;

  public function __construct()
  {
    $this->setInitData();
  }

  public function setDataProcess($campoValue, $campoName = null )
  {
    $this->campoValue =  is_string($campoValue) ? trim($campoValue) : $campoValue;
    $this->campoName = $campoName;
    $this->empcodi = empcodi();

    return $this;
  }

  public function getHeader( $campoName = null )
  {
    $campoName = $this->campoName ?? $campoName;
    return DataProductoProcess::getHeader($campoName);
  }

  public function setInitData()
  {
    return;
  }

  public function handle(&$dataProcess)
  {
  }
}
