<?php

namespace App\Http\Controllers\Util\Xml\Util\DocumentAnulacion;

class DocumentAnulacion 
{
  protected $documento;

  public function __construct($documento)
  {
    $this->documento = $documento;
  }

  public function processAnulacion()
  {
    $this->documento;
  }

  /**
   * Get the value of documento
   */ 
  public function getDocumento()
  {
    return $this->documento;
  }

  /**
   * Set the value of documento
   *
   * @return  self
   */ 
  public function setDocumento($documento)
  {
    $this->documento = $documento;

    return $this;
  }
}