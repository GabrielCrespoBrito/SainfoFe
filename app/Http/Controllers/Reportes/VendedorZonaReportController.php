<?php

namespace App\Http\Controllers\Reportes;

use App\Zona;
use App\Vendedor;
use App\PDFPlantilla;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFHtmlPdf;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\ReporteVendedorCliente;
use App\Util\ExcellGenerator\VendedorClienteExcell;

class VendedorZonaReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_VENDEDORZONA', 'R_REPORTE'));
  }

  public function create()
  {
    return view('reportes.vendedor.zona_create', [
      'vendedores' => Vendedor::all(),
      'zonas' => Zona::all(),
    ]);
  }

  public function report(Request $request)
  {
    $reporter = new ReporteVendedorCliente(
      $request->vendedor_id,
      $request->zona
    );

    
    $data_reporte = $reporter->getData();
    

    if (!$data_reporte) {
      noti()->warning('No existen registros, bajos los parametros seleccionados');
      return back();
    }

    if ($request->tipo_reporte === "0") {
      $view = view('reportes.vendedor.zona_pdf', $data_reporte);
      $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $pdf->generator->setGlobalOptions(PDFHtmlPdf::getSetting(PDFPlantilla::FORMATO_A4, false));
      return $pdf->generate();
    }
    // excell
    elseif ($request->tipo_reporte === "1") {
      ob_end_clean();
      $excellExport = new VendedorClienteExcell($data_reporte, 'reporte_vendedor_zona');
      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    }
  }
}
