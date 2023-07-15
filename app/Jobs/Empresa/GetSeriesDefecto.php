<?php

namespace App\Jobs\Empresa;

use App\PDFPlantilla;

/**
 * Obtener las series por defecto que van a utilizar los usuarios recientemente registrados
 * 
 */
class GetSeriesDefecto
{
  protected $plantillas_ids = [];
  public $series;

  public function __construct( $series = null)
  {
    $this->series = $series ??  config('app.default_series');
  }

  public function setPlantillasIds()
  {
    $pdfs = cacheHelper('pdfplantillas.defecto');

    # Ventas
    $a4_ventas = $pdfs->where('formato',  PDFPlantilla::FORMATO_A4)->where('tipo', 'ventas')->first()->id;
    $a5_ventas = $pdfs->where('formato',  PDFPlantilla::FORMATO_A5)->where('tipo', 'ventas')->first()->id;
    $ticket_ventas = $pdfs->where('formato',  PDFPlantilla::FORMATO_TICKET)->where('tipo', 'ventas')->first()->id;

    # Cotizaciones 
    $a4_cotizaciones = $pdfs->where('formato',  PDFPlantilla::FORMATO_A4)->where('tipo', 'cotizaciones')->first()->id;
    $a5_cotizaciones = optional($pdfs->where('formato',  PDFPlantilla::FORMATO_A5)->where('tipo', 'cotizaciones')->first())->id;
    $ticket_cotizaciones = optional($pdfs->where('formato',  PDFPlantilla::FORMATO_TICKET)->where('tipo', 'cotizaciones')->first())->id;

    # Guias
    $a4_guias = $pdfs->where('formato',  PDFPlantilla::FORMATO_A4)->where('tipo', 'guias')->first()->id;
    $a5_guias =  optional($pdfs->where('formato',  PDFPlantilla::FORMATO_A5)->where('tipo', 'guias')->first())->id;
    $ticket_guias = optional($pdfs->where('formato',  PDFPlantilla::FORMATO_TICKET)->where('tipo', 'guias')->first())->id;


    $this->plantillas_ids = [
      'ventas-a4' => $a4_ventas,
      'ventas-a5' => $a5_ventas,
      'ventas-ticket' => $ticket_ventas,
      'cotizaciones-a4' => $a4_cotizaciones,
      'cotizaciones-a5' => $a5_cotizaciones,
      'cotizaciones-ticket' => $ticket_cotizaciones,
      'guias-a4' => $a4_guias,
      'guias-a5' => $a5_guias,
      'guias-ticket' => $ticket_guias
    ];
  }

  public function getPlantillaIds($serie)
  {
    $tipo = $serie['tipo'];
    // 
    return (object) [
      'a4' => $this->plantillas_ids[$tipo . '-a4'],
      'a5' => $this->plantillas_ids[$tipo . '-a5'],
      'ticket' => $this->plantillas_ids[$tipo . '-ticket'],
    ];
  }

  public function  handle()
  {
    $this->setPlantillasIds();

    foreach ($this->series as &$serie) {
      $plantillas = $this->getPlantillaIds($serie);
      $serie['a4_plantilla_id'] = $plantillas->a4;
      $serie['a5_plantilla_id'] = $plantillas->a5;
      $serie['ticket_plantilla_id'] = $plantillas->ticket;
    }

    return $this->series;
  }
}
