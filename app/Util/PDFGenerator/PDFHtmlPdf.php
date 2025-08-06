<?php

namespace App\Util\PDFGenerator;

use App\PDFPlantilla;
use Illuminate\View\View;
use mikehaertl\wkhtmlto\Pdf;

class PDFHtmlPdf
{
  # Vista a procesar 
  protected $view;
  protected $settings;
  
  # Opciones principales de configuración
  protected $options = [
    'commandOptions' => [
      'useExec' => true,
      'escapeArgs' => false,
      'locale' => 'es_ES.UTF-8',
      'procOptions' => [
        'bypass_shell' => true,
        'suppress_errors' => true,
      ],
    ],
  ];


  public function getOptions()
  {
    return $this->options;
  }

  public function getView()
  {
    return $this->view;
  }  

  # Settings For A4 principales de configuración
  const SETTINGS_A4 = [
    'no-outline',
    'page-size' => 'A4',
    'orientation' => 'portrait',
    'margin-top' => '0.39in',
    'margin-right' => '0.39in',
    'margin-bottom' => '0.49in', 
    'margin-left' => '0.39in',
  ];

  # Settings For A5 principales de configuración
  const SETTINGS_A5 = [
    'no-outline',
    'margin-top' => '0in',
    'margin-right' => '0in',
    'margin-bottom' => '0in',
    'margin-left' => '0in',
    'page-size' => 'letter',
    'orientation' => 'landscape',
  ];

  # Settings For Ticket principales de configuración
  const SETTINGS_TICKET = [
    'no-outline',
    'page-width' => '7.5cm',
    'page-height' => '29.7cm',
    'margin-top' => '0in',
    'margin-right' => '0.2in',
    'margin-bottom' => '0in',
    'margin-left' => '0.1in',
    'orientation' => 'portrait',
  ];

  public static function getSetting($formato, $includeye_footer = true)
  {
    $settings = [
      PDFPlantilla::FORMATO_A4 => self::SETTINGS_A4,
      PDFPlantilla::FORMATO_A5 => self::SETTINGS_A5,
      PDFPlantilla::FORMATO_TICKET => self::SETTINGS_TICKET
    ][$formato];
  
    if( $formato == PDFPlantilla::FORMATO_A4 ){
      if($includeye_footer){
        $logEmpresa = get_option('LogEmpr');
        if(   $logEmpresa ){
          $dataEmpresa = json_decode($logEmpresa);
          if($dataEmpresa->footer_img){
            $html = sprintf("<!DOCTYPE html><html><body><div style='text-align:center'><img src='%s'></div></body></html>", $dataEmpresa->footer_img );
            $settings['footer-html'] = $html;
          }
        }
      }
    }
    return $settings;
  }


  public function setSettings( View $view, array $settings = [] )
  {
    $this->view = $view;
    $this->settings = $settings;
  }

  public function getGlobalOptions()
  {
    return $this->globalOptions;
  }

  # Configuración global
  protected $globalOptions = [
    'no-outline', 
    'page-size' => 'A4', 
    'orientation' => 'landscape',
    'footer-right' => 'Pagina:[page]/[topage]',
  ];

  # Obtener ubicación del ejecutable
  public function getBinary()
  {
    return get_setting('binary_path', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe');
  }

  /**
   * Poner opciones globales
   * 
   * @return void
   */
  public function setGlobalOptions( array $newOptions )
  {
    $this->globalOptions = array_merge( $this->globalOptions, $newOptions );
  }

  public function updatePageHeight( int $cm, $sum = false )
  {
    $pageHeight = str_replace('cm', '', $this->globalOptions['page-height']);
    $pageHeight = sprintf('%0.2fcm', $sum ? $pageHeight + $cm : $cm );

    $this->globalOptions['page-height'] = $pageHeight;

  }

  // Generar pdf 

  public function save( $path )
  {
    if( ! $this->view ){
      throw new \Exception("View is required", 1);
    }    
    $pdf = new Pdf( $this->getOptions() );
    // dd($this->getGlobalOptions());
    // exit();
    $pdf->setOptions( $this->getGlobalOptions());
    $pdf->addPage( $this->getView() );
    $pdf->binary = $this->getBinary();
    return $pdf->saveAs( $path );
  }


  public function toString() 
  {
    if ( !$this->view ) {
      throw new \Exception("View Is Required", 1);
    }

    $pdf = new Pdf($this->getOptions());
    $pdf->setOptions($this->getGlobalOptions());
    $pdf->addPage($this->getView());
    $pdf->binary = $this->getBinary();
    return $pdf->toString();
  }

  # Generar pdf
  public function generate()
  {
    if (!$this->view) {
      throw new \Exception("View is required", 1);
    }

    $pdf = new Pdf($this->getOptions());
    $pdf->setOptions($this->getGlobalOptions());
    $pdf->addPage($this->getView());
    $pdf->binary = $this->getBinary();
    
    if (!$pdf->send()) {
      throw new \Exception('Could not create PDF: ' . $pdf->getError());
    }
  }
}