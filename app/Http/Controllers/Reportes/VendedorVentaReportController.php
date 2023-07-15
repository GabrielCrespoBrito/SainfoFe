<?php

namespace App\Http\Controllers\Reportes;

use App\Local;
use App\Vendedor;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendedorVentaReportRequest;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\ReporteVendedorVenta;
use App\PDFPlantilla;
use App\Util\ExcellGenerator\VendedorVentaExcell;
use App\Util\PDFGenerator\PDFHtmlPdf;

class VendedorVentaReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_VENDEDORVENTAS', 'R_REPORTE'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('reportes.vendedor.ventas_create', [
      'vendedores' => Vendedor::all(),
      'locales' => Local::all()
    ]);
  }
  
  public function report( VendedorVentaReportRequest $request)
  {
    $reporter = new ReporteVendedorVenta(
      $request->vendedor_id,
      $request->local_id,
      $request->fecha_desde,
      $request->fecha_hasta,
      $request->cliente_id,
      $request->saldo,
    );

    $data_reporte = $reporter->getData();

    if( ! $data_reporte ){
      noti()->warning('No existen registros, bajos los parametros seleccionados');
      return back();
    }


    // pdf
    if(  $request->tipo_reporte === "0" ){
      $view = view('reportes.vendedor.ventas_pdf', $data_reporte);
      $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $pdf->generator->setGlobalOptions( PDFHtmlPdf::getSetting(PDFPlantilla::FORMATO_A4, false));
      return $pdf->generate();
    }

    // excell
    elseif ($request->tipo_reporte === "1") {
      ob_end_clean();

      $excellExport = new VendedorVentaExcell( $data_reporte, 'reporte_vendedor_ventas' );

      $info = $excellExport
      ->generate()
      ->store();

      return response()->download($info['full'], $info['file']);
      
    }
  }
}
