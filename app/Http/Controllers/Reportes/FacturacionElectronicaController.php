<?php

namespace App\Http\Controllers\Reportes;

use App\Venta;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFGenerator;
use App\Util\ExcellGenerator\FacturacionElectronicaExcell;

class FacturacionElectronicaController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_FACTURACION', 'R_REPORTE'))->only('searchTable');
  }

	public function search($request)
	{
		$tidDocs = $request->input('td') ? (array) $request->input('td') : ["01","07","08"];
		$fecha_emision = $request->input('fecha_emision');
		$fecha_final = $request->input('fecha_final');
		$estado_sunat = $request->input('estado_sunat');
		$empresa = get_empresa();

		$search = Venta::with(['cliente_with' => function($q){
      $q->where('TipCodi', 'C');
		},'statusSunat'])
		->whereIn('TidCodi', $tidDocs  )
		->whereBetween('VtaFvta', [ $fecha_emision , $fecha_final ]);

		// Estado de la sunat
		if ( $estado_sunat ) {
			$search->where('VtaFMail', '=', $estado_sunat );
		}

		return $search;
	}

  public function searchTable(Request $request)
  {
    $search = $this->search($request);
    return DataTables::of($search)->make(true);
  }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
    $this->authorize(p_name('A_FACTURACION', 'R_REPORTE'));

		return view('reportes.facturacion_electronica.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function pdf(Request $request)
	{
    $this->authorize(p_name('A_FACTURACION', 'R_REPORTE'));

		$tidDocs = $request->input('td') ? (array) $request->input('td') : ["01","07","08"];
		$tidDocs = implode(',', $tidDocs);
		$estado_nombre = $request->estado_sunat ?? 'Todos';
	
		$search = $this->search($request);
		$datas = $search->get();

		// dd($datas);

		if( $request->tipo_reporte === "pdf" ){
			$view = view('reportes.facturacion_electronica.pdf', ['datas' => $datas, 'fecha_inicio' => $request->fecha_emision, 'fecha_final' => $request->fecha_final]);
			$generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
			return $generator->generate();
		}

		else {
			ob_end_clean();
			$excellExport = new FacturacionElectronicaExcell(
			$datas,
			$request->fecha_emision,
			$request->fecha_final, 
			$tidDocs,
			$estado_nombre 
			);

			$info = $excellExport
				->generate()
				->store();

			return response()->download( $info['full'] , $info['file'] );
		}
	}
}


