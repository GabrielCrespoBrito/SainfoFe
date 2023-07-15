<?php

namespace App\Http\Controllers\Reportes;

use App\Caja;
use App\TipoPago;
use App\VentaPago;
use Dompdf\Dompdf;
use App\PDFPlantilla;
use App\ClienteProveedor;
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendedorEstadisticaRequest;
use App\Util\PDFGenerator\PDFHtmlPdf;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\ReporteVendedorEstadistica;
use App\Http\Requests\VentaTipoPagoReportRequest;

class VendedorEstadisticaReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_VENDEDORESTADISTICA', 'R_REPORTE'));
  }
  
  public function create()
  {
    return view('reportes.vendedor.estadisticas_create');
  }

  public function reportRender( Request $request )
  {
    $html = $request->hidden_html;    
    $pdf = new Dompdf();
    $pdf->loadHtml(view('reportes.vendedor.estadisticas_pdf_render', compact('html')));
    $pdf->render();
    return $pdf->stream("asdasd.pdf", ['Attachment' => false]);
  }

  public function report(VendedorEstadisticaRequest $request)
  {


    
    $reporter = new ReporteVendedorEstadistica(
      $request->fecha_desde,
      $request->fecha_hasta
    );

    $data_reporte = $reporter->getData();

    if (!$data_reporte) {
      noti()->warning('No existen registros, bajos los parametros seleccionados');
      return back();
    }

    return view('reportes.vendedor.estadistica_pdf', $data_reporte)->render();
  }
}
