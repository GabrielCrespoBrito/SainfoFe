<?php

namespace App\Http\Controllers\Reportes;

use App\Local;
use App\Vendedor;
use App\PDFPlantilla;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFHtmlPdf;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\ReporteVendedorVenta;
use App\Jobs\Reporte\ReporteVendedorCobertura;
use App\Http\Requests\VendedorCoberturaReportRequest;
use App\Util\ExcellGenerator\VendedorCoberturaExcell;

class VendedorCoberturaReportController extends Controller
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
    return view('reportes.vendedor.coberturas_create', [
      'vendedores' => Vendedor::all(),
      'locales' => Local::all()
    ]);
  }

  public function report(VendedorCoberturaReportRequest $request)
  {
    // dd($request->all());

    $reporter = new ReporteVendedorCobertura(
      $request->vendedor_id,
      $request->local_id,
      $request->fecha_desde,
      $request->fecha_hasta,
      $request->cliente_id,
      $request->saldo,
    );

    $data_reporte = $reporter->getData();

    // dd( $data_reporte );

    if (! $data_reporte) {
      noti()->warning('No existen registros, bajos los parametros seleccionados');
      return back();
    }


    // pdf
    // if ($request->tipo_reporte === "0") {
    //   $view = view('reportes.vendedor.ventas_pdf', $data_reporte);
    //   $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
    //   $pdf->generator->setGlobalOptions(PDFHtmlPdf::getSetting(PDFPlantilla::FORMATO_A4, false));
    //   return $pdf->generate();
    // }

    // excell
    // elseif ($request->tipo_reporte === "1") {
      ob_end_clean();

      $excellExport = new VendedorCoberturaExcell($data_reporte, 'reporte_vendedor_ventas');

      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    // }
  }
}
