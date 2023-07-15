<?php

namespace App\Http\Controllers\Reportes;

use App\Mes;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Cierre;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\Venta\VentaContableReport;
use App\Util\PDFGenerator\PDFGenerator;
use App\ReportData\VentaAnualReportData;
use App\Util\ExcellGenerator\VentaContableExcell;

class VentasController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_CONTABLEVENTASMENSUAL', 'R_REPORTE'))->only( 'ventas_mensual', 'ventas_mensual_pdf' );
    $this->middleware(p_midd('A_VENTASANUAL', 'R_REPORTE'))->only('ventas_anual', 'ventas_anual_pdf', 'ventas_anual_pdf_create');
  }

	public function ventas_anual()
	{
		return view('reportes.ventas_anual.form');
	}

	public function ventas_anual_pdf(Request $request)
	{
		$year = $request->year;
		$currrentMes = date('Y') == $year ? date('m') : 12;  
		$reportData = new VentaAnualReportData($year, $currrentMes);
		$data = $reportData->getData();
		return view('reportes.ventas_anual.pdf', compact('data'));
	}

	public function ventas_anual_pdf_create(Request $request, $year)
	{
    $html = $request->hidden_html;
    $pdf = new Dompdf();
    $pdf->loadHtml(view('reportes.ventas_anual.pdf_create', compact('html','year')));
    $pdf->render();
    return $pdf->stream("asdasd.pdf", ['Attachment' => false]);

    // $pdfGenerator = new PDFGenerator(view($view, compact('data', 'fecha_desde', 'fecha_hasta', 'local', 'titulo') ),  PDFGenerator::HTMLGENERATOR);
    // $pdfGenerator->generator->setGlobalOptions([
    //   'no-outline',
    //   'page-size' => 'Letter',
    //   'orientation' => 'portrait',
    // ]);
    // $pdfGenerator->generate();
	}

	public function ventas_mensual()
	{
		$view = auth()->user()->isContador() ?  'reportes.ventas_mensual.form_contador' : 'reportes.ventas_mensual.form';
		return view($view);
	}

	public function ventas_mensual_pdf(Request $request)
	{
		$empresa = get_empresa();
		$formato = $request->formato;
		$mescodi = $request->mes;
		$estadoSunat = $request->estado_sunat;
		$year = substr($mescodi, 0, 4);
		$mes = substr($mescodi, 4, 6);
    $fecha_inicio =  "{$year}-{$mes}-01";
    $carbon = new Carbon($fecha_inicio);
    $fecha_final =  $carbon->lastOfMonth()->format('Y-m-d');
    $report = new VentaContableReport($mescodi, $estadoSunat);

    if( $request->cerrar_mes ){
      Cierre::createIfNotExists($mescodi);
    }

    $data = $report
    ->handle()
    ->getData();

		set_time_limit(0);
		ini_set('memory_limit', '3000M'); //This might be too large, but depends on the data set
			
		// Formato PDF
		if ( $formato == "pdf" ) {

			$pdf = new Pdf([
				'commandOptions' => [
					'useExec' => true,
					'escapeArgs' => false,
					'locale' => 'es_ES.UTF-8',
					'procOptions' => [
						// This will bypass the cmd.exe which seems to be recommended on Windows
						'bypass_shell' => true,
						// Also worth a try if you get unexplainable errors
						'suppress_errors' => true,
					],
				],
			]);

			$globalOptions = ['no-outline', 'page-size' => 'Letter', 'orientation' => 'landscape'];

      $view = view('reportes.ventas_mensual.pdf', [
        'ventas_group' => $data['items'],
        'total' => $data['total'],
        'nombre_empresa' => $empresa->EmpNomb,
        'ruc_empresa' => $empresa->EmpLin1,
        'periodo' => Mes::find($request->mes)->mesnomb
      ]);

			$pdf->setOptions($globalOptions);
			$pdf->addPage($view);
			$pdf->binary = getBinaryPdf();

			if (!$pdf->send()) {
				throw new \Exception('Could not create PDF: ' . $pdf->getError());
			}
		}

		if( $formato == "html" ){

			return view('reportes.ventas_mensual.form', [
				'mes' 	  => $mescodi,
				'formato' => $formato,
				'estado_sunat' => $estadoSunat,
				'data_reporte' 		=> [
				'ventas_group' => $data['items'],
        'total' => $data['total'],
				'nombre_empresa' => $empresa->EmpNomb,
				'ruc_empresa' => $empresa->EmpLin1,
				'periodo' => Mes::find($request->mes)->mesnomb
				]
			]);

		}

		// excell 
		if( $formato == "excell" ){

			ob_end_clean();
			$nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
			$excellExport = new VentaContableExcell( $data, Mes::find($request->mes)->mesnomb, $nombreEmpresa );

			$info = $excellExport
				->generate()
				->store();
        
			return response()->download($info['full'], $info['file']);
		}
	}
}