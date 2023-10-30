<?php

namespace App\Jobs\PDFPlantilla;

use App\PDFPlantilla;

abstract class GetDataPDFAbstract {

  public $pdfPlantilla;
  public $empresa;
  public $empcodi;
  public $formato;
  public $plantilla_data;

  public function __construct($pdfPlantilla)
  {
    $this->pdfPlantilla = $pdfPlantilla;
    $this->plantilla_data = $pdfPlantilla->plantilla_data;
    
    $this->empcodi = empcodi();
    $this->empresa = get_empresa();
    $this->formato = $pdfPlantilla->formato;
  }
}