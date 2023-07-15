<?php

namespace App\Util\PDFGenerator;

use App\PDFPlantilla;
use Barryvdh\DomPDF\PDF;

class PDFDom
{
  # Vista a procesar 
  protected $view;
  protected $settings;

  # Settings For A4 principales de configuración
  const SETTINGS_A4 = [
  ];

  # Settings For A4 principales de configuración
  const SETTINGS_A5 = [
  ];

  # Settings For A4 principales de configuración
  const SETTINGS_TICKET = [
    'setPaper' => [0,0,205,1000],
  ];


  public static function getSetting($formato)
  {
    return [
      PDFPlantilla::FORMATO_A4     => self::SETTINGS_A4,
      PDFPlantilla::FORMATO_A5     => self::SETTINGS_A5,
      PDFPlantilla::FORMATO_TICKET => self::SETTINGS_TICKET
    ][$formato];
  }

  public function setSettings( $view, array $settings = [])
  {
    $this->view = $view;
    $this->settings = $settings;
  }

  # Configuración global
  protected $globalOptions = [
  ];

  /**
   * Poner opciones globales
   * 
   * @return void
   */
  public function setGlobalOptions(array $newOptions)
  {
    $this->globalOptions = array_merge($this->globalOptions, $newOptions);
  }

  /* Guardar pdf
   * 
   * Coño que depinga, que la pasen super bien
   * 
  */
  public function save($path)
  {
    $pdf = \PDF::loadView( $this->view->getName() , $this->view->getData() );

    if( $this->globalOptions['setPaper'] ){
      $pdf->setPaper( $this->globalOptions['setPaper'] );
    }

    $pdf->save($path);
  }


  public function toString()
  {
  }

  # Generar pdf
  public function generate()
  {
  }
}