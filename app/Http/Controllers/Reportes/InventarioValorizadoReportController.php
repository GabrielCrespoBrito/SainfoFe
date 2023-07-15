<?php

namespace App\Http\Controllers\Reportes;

use App\TipoExistancia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Moneda;
use App\Util\PDFGenerator\PDFGenerator;
use App\ReportData\InventarioValorizadoReportData;
use App\Util\ExcellGenerator\InventarioValorizadoExcell;
use App\Util\ExcellGenerator\VentaDetraccionExcell;

class InventarioValorizadoReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_INVENTARIOVALORIZADO', 'R_REPORTE'))->only('create', 'pdf');
  }

	public function create(Request $request)
	{
		$formato = 'linea';
		$show_report = false;
		$data_report = null;
		$loccodi = "001";
		$moncodi = "001";
		$tipo_existencia_id = null;
		$locales = get_empresa()->locales()->get();
		$tipos_existencia = TipoExistancia::get();
		return view('reportes.inventario_valorizado.create', compact('formato', 'loccodi', 'moncodi', 'tipos_existencia', 'tipo_existencia_id', 'locales', 'show_report', 'data_report'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function pdf(Request $request)
	{
		$this->validate($request, [
			'local' => 'required',
			'moneda' => 'required|in:01,02',
			'tipo_existencia' => 'sometimes|nullable',
			'formato' => 'required|in:pdf,excell,html',
		]);

		$empresa = get_empresa();
		$loccodi = $request->local;
		$moncodi = $request->moneda;
		$tipo_existencia_id = $request->tipo_existencia;
		$dataReport = new InventarioValorizadoReportData( $loccodi, $moncodi, $tipo_existencia_id );
		$data_report = [];
		$tipos_existencia = TipoExistancia::get();
		$tipo_existencia_nombre = $request->tipo_existencia ? 
		$tipos_existencia->where('TieCodi' , $request->tipo_existencia)->first()->TieNomb :
		'TODOS';
		$locales = get_empresa()->locales()->get();
		$data_report['data'] = $dataReport->getData();
		// dd( $data_report['data'] );
		$data_report['total_general'] = $dataReport->total_general;
		$data_report['nombre_empresa'] = $empresa->EmpNomb;
		
		$data_report['tipo_existencia_nombre'] = $tipo_existencia_nombre;
		$data_report['local_nombre'] = $locales->where('LocCodi', $request->local)->first()->LocNomb;
		$data_report['moneda_nombre'] = Moneda::getNombre($request->moneda);
		$data_report['ruc_empresa'] = $empresa->EmpLin1;
		$data_report['isPDF'] = $request->formato == 'pdf';

		switch ($request->formato) {
			case 'html':
				$formato = 'linea';
				$show_report = true;
				return view('reportes.inventario_valorizado.create', compact('formato', 'loccodi', 'moncodi', 'tipo_existencia_id', 'tipos_existencia', 'locales', 'show_report', 'data_report' ));
				break;
			case 'pdf':
				$view = view('reportes.inventario_valorizado.pdf', ['data_report' => $data_report ]);
				$generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR, ['']);
				$generator->generator->setGlobalOptions(['orientation' => 'portrait']);
				$generator->generate();
				break;
			case 'excell':
				ob_end_clean();
				$nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
				$excellExport = new  InventarioValorizadoExcell( $data_report );
				$info = $excellExport
					->generate()
					->store();
				return response()->download($info['full'], $info['file']);
		}
	}
}
