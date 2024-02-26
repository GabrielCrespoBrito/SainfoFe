<?php

namespace App\Util\PDFGenerator;

use Exception;
use App\PDFPlantilla;
use Barryvdh\DomPDF\PDF;

class PDFGenerator
{
  const HTMLGENERATOR = "htmlGenerator";
  const DOMGENERATOR  = "dom";
  public $generator;

  public function __construct( $view, $typeGenerator = self::DOMGENERATOR  , $settings  = [])
  {
    $this->generator = $typeGenerator ==  self::DOMGENERATOR  ? new PDFDom() : new PDFHtmlPdf();
    $this->generator->setSettings($view, $settings);
  }

  public static function getSetting( $formato , $generator, $includeye_footer = true)
  {
    switch ($generator) 
    {
      case self::HTMLGENERATOR:
        return PDFHtmlPdf::getSetting($formato, $includeye_footer);
        break;
      // 
      case self::DOMGENERATOR:
        return PDFDom::getSetting($formato, $includeye_footer);
        break;
      // 
      default:
      throw new Exception("The Generator {$generator} don't exists", 1);
      break;
    }
  }

  /**
   * Generar pdf
   * 
   * @return 
   */
  public function generate()
  {
    return $this->generator->generate();
  }

  public function save( $path )
  {
    return $this->generator->save( $path );
  }

}