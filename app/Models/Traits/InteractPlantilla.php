<?php

namespace App\Models\Traits;

use App\Cotizacion;
use App\GuiaSalida;
use App\PDFPlantilla;
use App\SerieDocumento;
use App\TipoDocumentoPago;
use App\Venta;

trait InteractPlantilla
{
  public function getDataDoc()
  {
    $data = [];

    if( $this instanceof Venta ){
      return (object) [
        'empcodi' => $this->EmpCodi,
        'serie' => $this->VtaSeri,
        'tidcodi' => $this->TidCodi,
        'loccodi' => $this->LocCodi,
        'usucodi' => $this->UsuCodi,  
      ];
    }

    if ($this instanceof GuiaSalida) {

      $serie = $this->hasFormato() ? $this->GuiSeri : null;

      return (object) [
        'empcodi' => $this->EmpCodi,
        'serie' => $serie,
        'tidcodi' => $this->getTipoDocumento(),
        'loccodi' => $this->Loccodi,
        'usucodi' => $this->usucodi,
      ];
    }

    if ($this instanceof Cotizacion) {

      $serie_numero = explode('-', $this->CotNume);

      return (object) [
        'empcodi' => $this->EmpCodi,
        'serie' => $serie_numero[0],
        'tidcodi' => $this->TidCodi1,
        'loccodi' => $this->LocCodi,
        'usucodi' => $this->usucodi,
      ];
    }    

  }

  /**
   * Obtener serie de la venta
   * 
   * @return SerieDocumento
   */
  public function getSerie($withSerie = true)
  {
    $data_doc = $this->getDataDoc();

    $serie = $withSerie ? $data_doc->serie : null;

    return SerieDocumento::findSerie(
      $data_doc->empcodi,
      $serie,
      $data_doc->tidcodi,
      $data_doc->loccodi,
      $data_doc->usucodi,      
    )->first();



  }


  public function getPlantilla($formato, $withSerie = true)
  {
    $serie = $this->getSerie($withSerie);

    return [
      // PDFPlantilla::FORMATO_A4 => $this->getSerie($withSerie)->plantillaA4()->first(),
      // PDFPlantilla::FORMATO_A5 => $this->getSerie($withSerie)->plantillaA5()->first(),
      // PDFPlantilla::FORMATO_TICKET => $this->getSerie($withSerie)->plantillaTicket()->first(),
      PDFPlantilla::FORMATO_A4 => $serie->plantillaA4()->first(),
      PDFPlantilla::FORMATO_A5 => $serie->plantillaA5()->first(),
      PDFPlantilla::FORMATO_TICKET => $serie->plantillaTicket()->first(),      
    ][$formato];


    // if ($formato == PDFPlantilla::FORMATO_A4) {
    //   return $this->getSerie()->plantillaA4()->first();
    // }
    // ----------------
    // if ($formato == PDFPlantilla::FORMATO_A5) {
    //   return $this->getSerie()->plantillaA5()->first();
    // }

    // if ($formato == PDFPlantilla::FORMATO_TICKET) {
    //   return $this->getSerie()->plantillaTicket()->first();
    // }
  }
}