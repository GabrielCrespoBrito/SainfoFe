<?php

namespace App\Jobs\Venta;

use App\Venta;
use App\VentaItem;
use App\PDFPlantilla;
use App\Util\PDFGenerator\PDFGenerator;

class GeneratePrevPDF
{
  public $request;
  public function __construct($request)
  {
    $this->request = $request;
  }

  public function handle()
  {
    $request = $this->request;
    $documento = Venta::createFactura($request, false, $request->total_documento, false);
    $items = VentaItem::createItem( $documento->VtaOper, $request->items, false, $request->totales_items, $request->placa_vehiculo, false);
    $items = collect($items);
    return $documento->generatePDF(PDFPlantilla::FORMATO_A4, false, true, $request->serie->impresion_directa, $generator = PDFGenerator::HTMLGENERATOR, $items);
  }
}
