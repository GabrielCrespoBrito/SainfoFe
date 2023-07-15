<?php

namespace App\Http\Controllers\Caja;

use App\Caja;
use App\Util\PDFGenerator\PDFGenerator;
use App\Venta;

trait ReporteSimplificadoTrait
{
  public function reporteSimplificado( $id, $formato = Venta::FORMATO_A4 )
  {
    $caja = Caja::find($id);
    $data = $caja->getReporteCajaSimplificadoData();
    $pdfGenerator = new PDFGenerator(view('cajas.resumen_simplificado_pdf',$data), PDFGenerator::HTMLGENERATOR);
    $pdfGenerator->generator->setGlobalOptions(PDFGenerator::getSetting($formato, PDFGenerator::HTMLGENERATOR));
    return $pdfGenerator->generator->generate();
  }

}

