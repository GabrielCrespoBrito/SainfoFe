<?php

namespace App\Http\Controllers\Reportes;

use App\Marca;
use App\Vendedor;
use App\PDFPlantilla;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFHtmlPdf;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\ReporteVendedorProducto;
use App\Http\Requests\VendedorVentaReportRequest;
use App\Util\ExcellGenerator\VendedorProductoExcell;

class VendedorProductoReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_VENDEDORPRODUCTO', 'R_REPORTE'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('reportes.vendedor.producto_create', [
      'vendedores' => Vendedor::all(),
      'marcas' => Marca::all()
    ]);
  }
  
  public function report( VendedorVentaReportRequest $request)
  {
    $reporter = new ReporteVendedorProducto(
      $request->vendedor_id,
      $request->marca_id,
      $request->fecha_desde,
      $request->fecha_hasta
    );
    
    $data_reporte = $reporter->getData();
    
    if( ! $data_reporte ){
      noti()->warning('No existen registros, bajos los parametros seleccionados');
      return back();
    }

    if( $request->tipo_reporte === "0" ){
      $view = view('reportes.vendedor.producto_pdf', $data_reporte);
      $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $pdf->generator->setGlobalOptions( PDFHtmlPdf::getSetting(PDFPlantilla::FORMATO_A4, false));
      return $pdf->generate();
    }

    // excell
    elseif ($request->tipo_reporte === "1") {
      ob_end_clean();

      $excellExport = new VendedorProductoExcell( $data_reporte, 'reporte_vendedor_productos' );

      $info = $excellExport
      ->generate()
      ->store();

      return response()->download($info['full'], $info['file']);
      
    }
  }
}
