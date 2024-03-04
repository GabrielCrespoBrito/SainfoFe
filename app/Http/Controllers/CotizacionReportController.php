<?php

namespace App\Http\Controllers;

use App\Vendedor;
use App\PDFPlantilla;
use Illuminate\Http\Request;
use App\Util\PDFGenerator\PDFHtmlPdf;
use App\Jobs\Reporte\ReporteCotizacion;
use App\Util\PDFGenerator\PDFGenerator;
use App\Util\ExcellGenerator\CotizacionExcell;

class CotizacionReportController extends Controller
{
  public function __construct()
  {
    // $this->middleware(p_midd('A_VENDEDORZONA', 'R_REPORTE'));
  }

  public function create()
  {
    return view('reportes.cotizacion.create', [
      'vendedores' => Vendedor::all(),
      'usuarios' => get_empresa()->users
    ]);
  }

  public function report(Request $request)
  {
    $reporter = new ReporteCotizacion(
      $request->vencodi,
      $request->usucodi,
      $request->estado,
      $request->fecha_desde,
      $request->fecha_hasta);


    $data_reporte = $reporter->getData();

    if (!$data_reporte) {
      noti()->warning('No existen registros, bajos los parametros seleccionados');
      return back();
    }

    if ($request->tipo_reporte === "0") {
      $view = view('reportes.cotizacion.pdf', $data_reporte);
      $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $pdf->generator->setGlobalOptions(PDFHtmlPdf::getSetting(PDFPlantilla::FORMATO_A4, false));
      return $pdf->generate();
    }

    elseif ($request->tipo_reporte === "1") {
      ob_end_clean();
      $excellExport = new CotizacionExcell($data_reporte, 'reporte_vendedor_zona');
      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    }
  }
}
