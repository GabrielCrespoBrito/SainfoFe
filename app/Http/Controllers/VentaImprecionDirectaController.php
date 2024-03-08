<?php

namespace App\Http\Controllers;

use App\Venta;
use App\PDFPlantilla;
use Illuminate\Http\Request;

class VentaImprecionDirectaController extends Controller
{
  public function show(Request $request)
  {
    $venta = Venta::find($request->id_venta);
    $pdfResult = $venta->generatePDF(PDFPlantilla::FORMATO_TICKET, false, false, true);
    $dataPDF = Venta::prepareDataVentaForJavascriptPrint($pdfResult['data']);

    return $dataPDF;
  }
}
