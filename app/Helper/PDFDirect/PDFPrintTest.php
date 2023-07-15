<?php

namespace App\Helper\PDFDirect;

use Exception;
// use Mike42\Escpos\Printer;
// use Mike42\Escpos\EscposImage;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use App\Empresa;
use App\Util\Mike42\Escpos\Printer;
use Illuminate\Support\Facades\Log;
use App\Util\Mike42\Escpos\EscposImage;
use App\Util\Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PDFPrintTest
{
  public $printName;
  public $printer;
  public $conector;
  public $empresa;

  /**
   * Separador de linea con un caracter especifico
   */
  protected $separator = '-';

  /**
   * Cantidad de caracteres del total para la linea
   */
  protected $widthLine = 48;

  public function __construct( Empresa $empresa,  string $printName)
  {
    $this->empresa = $empresa;
    $this->printName = $printName;
    $this->lineHeaderItem = new LineItemHeader($this->widthLine);
  }

  /**
   * Obtener el separador 
   * 
   * @return mixed
   */
  public function getSeparator()
  {
    return $this->separator;
  }

  /**
   * Obtener el separador 
   * 
   * @return $this
   */
  public function setSeparator($separator)
  {
    $this->separator = $separator;

    return $this;
  }

  public function getWidthLine()
  {
    return $this->widthLine;
  }

  public function setCaracterQty(int $widthLine)
  {
    $this->widthLine = $widthLine;

    return $this;
  }

  public function getLineSeparator()
  {
    $this->printer->text($this->getSeparatorLine());
  }


  /**
   * Ejecutar la impresiòn
   * 
   * @return object
   */
  public function print()
  {
    try {
      $this->connector = new WindowsPrintConnector($this->printName);
      // $this->connector = new NetworkPrintConnector("192.168.1.52", 9100);
      $this->printer = new Printer($this->connector);
      $this->generateHeader();
      $this->getLineSeparator();
      $this->generateMessage();
      $this->printer->cut();
      $this->printer->pulse();
    } catch (Exception $e) {
    } finally {
      try {
        optional($this->printer)->close();
      } catch (Exception $th) {
        return (object) [
          'response' => false ,
          'message' =>  $th->getMessage() 
        ];
      }
    }

    return (object) [
      'response' => true,
      'message' => '' 
    ];
  }

  /**
   * Adicional a un string el caracter para nueva linea
   * 
   * @return string
   */
  public function addLine($str)
  {
    return $str . "\n";
  }

  /**
   * Cabecera del ticket
   * 
   * @return void
   */
  public function generateHeader()
  {
    $this->printer->setJustification(Printer::JUSTIFY_CENTER);

    // Logotipo
    if ( $this->empresa->EmpLogo1 ) {
      $logoPath = getTempPath(time() . '.png', $this->empresa->EmpLogo1 );
      $logo = EscposImage::load($logoPath, false);
      $this->printer->bitImage($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
      $this->printer->feed();
    }

    // Datos de la empresa
    $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $this->printer->text($this->addLine( $this->empresa->EmpNomb ));
    $this->printer->selectPrintMode();
    $this->printer->text($this->addLine( $this->empresa->direccion()  ));
    $this->printer->text($this->addLine( $this->empresa->telefonos() ));
    $this->printer->text($this->addLine( $this->empresa->email()  ));
  }


  /**
   * Mensaje del ticket del ticket
   * 
   * @return void
   */
  public function generateMessage()
  {
    $this->printer->setJustification(Printer::JUSTIFY_CENTER);
    $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $this->printer->text($this->addLine(  'PRUEBA DE IMPRESIÒN' ));
    $this->printer->text($this->addLine('IMPRESORA: ' . $this->printName ));
    $this->printer->selectPrintMode();
  }

  


  /**
   * Generar linea de separaciòn
   * @return 
   */
  public function getSeparatorLine()
  {
    return $this->addLine(str_repeat($this->getSeparator(), $this->getWidthLine()));
  }
}
