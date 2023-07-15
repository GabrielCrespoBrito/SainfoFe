<?php

namespace App\Http\Controllers;

use App\CajaDetalle;
use App\M;
use App\PDFPlantilla;
use App\Util\PDFGenerator\PDFGenerator;
use App\Util\PDFGenerator\PDFHtmlPdf;

class CajaDetallesController extends Controller
{
  public function pdf( $movimiento_id )
  {
    // loremp-ipsum-odlor-loremp-ipsum-odlor
    $movimiento = CajaDetalle::find($movimiento_id);
    $data_movimiento = $movimiento->getDataForPDF();
    $empresa = get_empresa();
    $nombre_reporte = "RECIBO DE EGRESO: " . $movimiento->Id;
    $ruc = $empresa->ruc();
    $nombre_empresa = $empresa->nombre();

    $pdf_generator = new PDFGenerator(view('cajas.pdf_movimiento_egreso', compact('movimiento', 'data_movimiento', 'nombre_reporte', 'ruc', 'nombre_empresa')), PDFGenerator::HTMLGENERATOR);
    $pdf_generator->generator->setGlobalOptions( PDFHtmlPdf::getSetting(PDFPlantilla::FORMATO_A4, false));
    return $pdf_generator->generate();
  }
}