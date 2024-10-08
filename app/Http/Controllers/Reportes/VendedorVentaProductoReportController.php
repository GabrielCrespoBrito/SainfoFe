<?php

namespace App\Http\Controllers\Reportes;


use App\Marca;
use App\Vendedor;
use App\PDFPlantilla;
use App\TipoDocumentoPago;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFHtmlPdf;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Requests\VendedorVentaReportRequest;
use App\Jobs\Reporte\ReporteVendedorVentaProducto;
use App\Util\ExcellGenerator\VendedorVentaProductoExcell;

class VendedorVentaProductoReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_VENDEDOVENTARPRODUCTO', 'R_REPORTE'));
  }
  
  public function create()
  {
    return view('reportes.vendedor.venta_producto_create', [
      'vendedores' => Vendedor::all(),
      'marcas' => Marca::NoDeleted()->get(),
      'tdocumentos' =>  TipoDocumentoPago::getTiposVentas(),
    ]);
  }

  public function report(VendedorVentaReportRequest $request)
  {
    $reporter = new ReporteVendedorVentaProducto(
      $request->vendedor_id,
      $request->tipodocumento_id,
      $request->marca_id,
      $request->fecha_desde,
      $request->fecha_hasta
    );

    $data_reporte = $reporter->getData();

    if (!$data_reporte) {
      noti()->warning('No existen registros, bajos los parametros seleccionados');
      return back();
    }

    if ($request->tipo_reporte === "0") {
      $view = view('reportes.vendedor.venta_producto_pdf', $data_reporte);
      $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $pdf->generator->setGlobalOptions(PDFHtmlPdf::getSetting(PDFPlantilla::FORMATO_A4, false));
      return $pdf->generate();
    }
    // excell
    elseif ($request->tipo_reporte === "1") {
      ob_end_clean();
      $excellExport = new VendedorVentaProductoExcell($data_reporte, 'reporte_vendedor_productos');
      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    }
  }
}