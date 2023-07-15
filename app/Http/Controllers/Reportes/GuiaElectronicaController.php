<?php

namespace App\Http\Controllers\Reportes;

use App\GuiaSalida;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Guia\Guia;
use App\Util\PDFGenerator\PDFGenerator;
use App\Util\ExcellGenerator\GuiaElectronicaExcell;
use App\Util\ExcellGenerator\FacturacionElectronicaExcell;

class GuiaElectronicaController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_GUIAELECTRONICA', 'R_REPORTE'))->only(['show']);
  }

	public function search($request)
	{
		$tidDocs = $request->input('td') ? (array) $request->input('td') : ["01", "07", "08"];
		$fecha_emision = $request->input('fecha_emision');
		$fecha_final = $request->input('fecha_final');
		$estado_sunat = $request->input('estado_sunat');
		$empresa = get_empresa();
		$empcodi = $empresa->empcodi;

		$database = $empresa->getDataBase();

		$search = \DB::connection('tenant')->table($database . '.guias_cab')
			->join('prov_clientes', function ($join) {
				$join
					->on('prov_clientes.PCCodi', '=', 'guias_cab.PCCodi')
					->on('prov_clientes.EmpCodi', '=', 'guias_cab.EmpCodi')
					->where('prov_clientes.TipCodi', '=', 'C');
			})
			->where('guias_cab.EmpCodi', '=', $empcodi )
			->where('guias_cab.GuiEFor', '=', GuiaSalida::CON_FORMATO )
			->whereBetween('guias_cab.GuiFemi', [ $fecha_emision , $fecha_final ])
			->orderBy('guias_cab.GuiNumee');

		if ( $estado_sunat  == "0003" ) {
			$search->where('guias_cab.GuiEsta', '=', "A" );
		}

		if ($estado_sunat) {
				switch ($estado_sunat) {
				case '0001':
					$search->where('guias_cab.fe_rpta', '=' , 0 );
				break;
				case '0002':
						$search->where('guias_cab.fe_rpta', '!=', [0,9] );
				break;
				case '0011':
					$search->where('guias_cab.fe_rpta', '=', 9 );
				break;
				}
			}

		
		$search->select(
			'guias_cab.GuiOper',
			'guias_cab.GuiNume',
			'guias_cab.GuiSeri',
			'guias_cab.GuiNumee',
			'guias_cab.fe_rpta',
			'guias_cab.GuiEsta',			
			'guias_cab.fe_obse',
			'guias_cab.GuiFemi',
			'prov_clientes.PCNomb',
			'prov_clientes.PCRucc'
		);


		return $search;
	}

	public function searchTable(Request $request)
	{
		$search = $this->search($request);
		return DataTables::of($search)
			->make(true);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		return view('reportes.guia_electronica.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function pdf(Request $request)
	{
    $this->authorize(p_name('A_GUIAELECTRONICA', 'R_REPORTE'));

		$estado_nombre = $request->estado_sunat ?? 'Todos';
		$search = $this->search($request);
		$datas = $search->get();

		if ($request->tipo_reporte === "pdf") {
			$view = view('reportes.guia_electronica.pdf', ['datas' => $datas, 'fecha_inicio' => $request->fecha_emision, 'fecha_final' => $request->fecha_final]);
			$generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
			return $generator->generate();
		} 
		
		else {
			ob_end_clean();
			$excellExport = new GuiaElectronicaExcell(
				$datas,
				$request->fecha_emision,
				$request->fecha_final,
				$estado_nombre
			);

			$info = $excellExport
				->generate()
				->store();

			return response()->download($info['full'], $info['file']);
		}
	}
	//
}
