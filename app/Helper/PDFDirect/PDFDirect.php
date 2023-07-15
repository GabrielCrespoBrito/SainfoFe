<?php

namespace App\Helper\PDFDirect;

use Exception;
// use Mike42\Escpos\Printer;
// use Mike42\Escpos\EscposImage;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use App\Util\Mike42\Escpos\Printer;
use Illuminate\Support\Facades\Log;
use App\Helper\PDFDirect\LineSubtotal;
use App\Util\Mike42\Escpos\EscposImage;
use App\Util\Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use App\Util\Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PDFDirect
{
  public $printName;
  public $printer;
  public $copyNumber = 1;
  public $conector;
  public $dataVenta;

  /**
   * Separador de linea con un caracter especifico
   */
  protected $separator = '-';

  /**
   * Cantidad de caracteres del total para la linea
   */
  protected $widthLine = 48;

  public function __construct(string $printName, array $dataVenta, int $copyNumber)
  {
    $this->printName = $printName;
    $this->dataVenta = $dataVenta;
    $this->copyNumber = $copyNumber;


    $this->lineHeaderItem = new LineItemHeader($this->widthLine);
    $this->lineHeaderTotal = new LineItemtotal($this->widthLine, 5);
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
   * @return bool
   */
  public function print()
  {
    for ($i = 0; $i < $this->copyNumber; $i++) {

      try {
        $this->connector = new WindowsPrintConnector($this->printName);
        // $this->connector = new NetworkPrintConnector("192.168.1.52", 9100);
        $this->printer = new Printer($this->connector);
        $this->generateHeader($i);
        $this->getLineSeparator();
        $this->generateDocInfo();
        $this->getLineSeparator();
        $this->generateClientInfo();
        $this->getLineSeparator();
        $this->generateItemsSection();
        $this->getLineSeparator();
        $this->generateTotalsSection();
        $this->getLineSeparator();
        $this->generateQrSection();
        $this->generateFooterSection();
        $this->printer->cut();
        $this->printer->pulse();
      } catch (Exception $e) {
      } finally {
        try {
          optional($this->printer)->close();
        } catch (Exception $th) {
          return false;
        }
      }

    }
    return true;
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
  public function generateHeader($index)
  {
    $this->printer->setJustification(Printer::JUSTIFY_CENTER);
    // $this->printer->text( "çPRINT HOJA {$index}");
    // return;

    // Logotipo
    if ($this->dataVenta['empresa']['EmpLogo1']) {
      $logoPath = getTempPath(time() . '.png', $this->dataVenta['empresa']['EmpLogo1']);
      $logo = EscposImage::load($logoPath, false);
      $this->printer->bitImage($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
      $this->printer->feed();
    }

    // Datos de la empresa
    $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $this->printer->text($this->addLine($this->dataVenta['empresa']['EmpNomb']));
    $this->printer->selectPrintMode();
    $this->printer->text($this->addLine($this->dataVenta['direccion']));
    $this->printer->text($this->addLine("{$this->dataVenta['cliente']->getNombreTipoDocumento()}:" . $this->dataVenta['empresa']['EmpLin1']));
    $this->printer->text($this->addLine($this->dataVenta['telefonos']));
    $this->printer->text($this->addLine($this->dataVenta['correo']));

    // Numeraciòn del documento
    $this->printer->setEmphasis(true);
    $this->printer->text($this->addLine($this->dataVenta['nombre_documento']));
    $this->printer->setEmphasis(false);
    $this->printer->text($this->addLine($this->dataVenta['documento_id']));
  }


  /**
   * Informaciòn del documento
   * 
   * @return void
   */
  public function generateDocInfo()
  {
    $this->printer->setJustification(Printer::JUSTIFY_LEFT);
    $this->printer->text($this->addLine('Fecha Emisiòn: ' .  $this->dataVenta['venta']['VtaFvta']  . ' ' . $this->dataVenta['venta']['VtaHora']));
    $this->printer->text($this->addLine("Vendedor: {$this->dataVenta['venta']['Vencodi']}"));
  }

  /**
   * Informaciòn del documento
   * 
   * @return void
   */
  public function generateClientInfo()
  {
    $this->printer->text($this->addLine("{$this->dataVenta['cliente']->getNombreTipoDocumento()} cliente: " .  $this->dataVenta['cliente']['PCRucc']));
    $this->printer->text($this->addLine("Cliente: {$this->dataVenta['cliente']['PCNomb']} "));
    $this->printer->text($this->addLine("Dir: {$this->dataVenta['cliente']['PCDire']} "));
  }

  /**
   * Informaciòn del documento
   * 
   * @return void
   */
  public function generateQrSection()
  {
    $this->printer->setJustification(Printer::JUSTIFY_LEFT);
    $this->printer->text($this->addLine("Forma de pago: {$this->dataVenta['forma_pago']->connomb}"));
    $this->printer->text($this->addLine("Codigo hash: {$this->dataVenta['venta']['fe_firma']}"));
    $this->printer->feed();

    $printer2 = new Printer($this->connector); // dirty printer profile hack !!
    $printer2->setJustification(Printer::JUSTIFY_CENTER);
    $printer2->qrCode($this->dataVenta['qrData'], Printer::QR_ECLEVEL_M, 5);
    $printer2->setJustification();
    $printer2->feed();
  }


  public function generateItemsSection()
  {
    // Cabecera
    $this->printer->text($this->lineHeaderItem->getAsString('Unid', 'Descripciòn'));
    $this->printer->text($this->lineHeaderTotal->getAsString('Cant.', 'V.Unit.', 'P.Unit.', 'Dcto.', 'Importe.'));
    $this->getLineSeparator();

    // Iteracion de items        
    $items = $this->dataVenta['venta2']->items;
    $decimals = $this->dataVenta['decimals'];

    foreach ($items as $item) {

      // Unidad y Nombre del producto
      $this->printer->text($this->lineHeaderItem->getAsString($item->DetUnid, $item->DetNomb));

      // Totales
      $this->printer->text($this->lineHeaderTotal->getAsString(
        $item->DetCant,
        decimal($item->precioUnitario(), $decimals),
        decimal($item->valorUnitario(), $decimals),
        decimal($item->DetDcto, $decimals),
        decimal($item->DetImpo, $decimals)
      ));
    }
  }


  /**
   * Secciòn de totales section
   * 
   * @return void
   */
  public function generateTotalsSection()
  {
    $lineSubTotal = new LineSubtotal($this->dataVenta['moneda_abreviatura'], $this->widthLine, 16);

    $venta = $this->dataVenta['venta2'];

    $this->printer->text($lineSubTotal->getAsString('OP Gravadas:', $venta->Vtabase));

    if ($venta->hasMontoExonerado()) {
      $this->printer->text($lineSubTotal->getAsString('OP Exonerada:', $venta->VtaExon));
    }

    if ($venta->hasMontoGrauito()) {
      $this->printer->text($lineSubTotal->getAsString('OP Gratuita:', $venta->VtaGrat));
    }

    if ($venta->hasMontoInafecto()) {
      $this->printer->text($lineSubTotal->getAsString('OP Inafecta:', $venta->VtaInaf));
    }

    if ($venta->hasMontoDcto()) {
      $this->printer->text($lineSubTotal->getAsString('Descuento total:', $venta->VtaDcto));
    }

    if ($venta->hasMontoICBPER()) {
      $this->printer->text($lineSubTotal->getAsString('I.C.B.P.E.R:', $venta->icbper));
    }

    $this->printer->text($lineSubTotal->getAsString('I.G.V:', $venta->VtaIGVV));
    $this->printer->setEmphasis(true);
    $this->printer->text($lineSubTotal->getAsString('Total Venta:', $venta->VtaImpo));
    $this->printer->setEmphasis(false);
  }

  /**
   * Footer section
   * 
   * @return void
   */
  public function generateFooterSection()
  {
    $this->printer->setJustification(Printer::JUSTIFY_CENTER);
    $this->printer->text($this->addLine("Representaciòn impresa de la {$this->dataVenta['nombre_documento']}"));
    $this->printer->text($this->addLine('Este documento puede ser consultado en:'));
    $this->printer->text($this->addLine(config('app.url_busqueda_documentos')));
    $this->printer->setEmphasis(true);
    $this->printer->text($this->addLine('*** GRACIAS POR SU COMPRA ***'));
    $this->printer->setEmphasis(false);
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
