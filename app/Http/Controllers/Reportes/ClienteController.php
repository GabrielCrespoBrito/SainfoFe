<?php

namespace App\Http\Controllers\Reportes;
use Carbon\Carbon;
use App\ClienteProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReporteMejorClienteRequest;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\MejoresClientesReportData;

class ClienteController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_CLIENTEDEUDA', 'R_REPORTE'))->only(['deudas', 'deudasPdf']);
    $this->middleware(p_midd('A_MEJORESCLIENTES', 'R_REPORTE'))->only(['mejoresClientes', 'mejoresClientesPdf']);
  }

	public function deudasPdf( Request $request )
	{
		$data = [];
		$empresa = get_empresa();
		$empcodi = $empresa->empcodi;

		$data['titulo'] = "Reporte clientes";
		$fecha = $data['fecha'] = $request->input('fecha_desde');
		$totales = [ 'pedido'  => 0, 'saldo' => 0, 'cobranza' => 0 ];

		$clientes = DB::connection('tenant')->table('prov_clientes')
		->join('ventas_cab', 'ventas_cab.PCCodi', '=', 'prov_clientes.PCCodi' )
		->where('ventas_cab.EmpCodi', $empcodi )
		->where('prov_clientes.EmpCodi', $empcodi )
		->where('ventas_cab.VtaFvta', $fecha )
		->where('prov_clientes.TipCodi', 'C' )
		->select('prov_clientes.PCCodi')
		->get()
		->groupBy('PCCodi')
		->keys();

		$data['clientes'] = $clientes->map(function($user,$key) use( $fecha , &$totales ) {


			$data = [];
			$user = ClienteProveedor::find($user);
			$ventas = $user->ventas;

			if( $ventas->where('VtaFvta', '=', $fecha)->count() ){

				$pedido = $ventas->where('VtaFvta', $fecha )->sum('VtaImpo');
				$saldo = $ventas->where('VtaFvta', $fecha )->sum('VtaSald');
				$cobranza = fixedValue($ventas->where('VtaFvta' , '<' , $fecha )->sum('VtaSald'));

				$data['nombre'] = $user->PCNomb;
				$data['ruc'] = $user->PCRucc;
				$data['pedidos'] = fixedValue($pedido);
				$data['saldo'] = fixedValue($saldo);
				$data['cobranza'] = $cobranza;

				$totales['pedido'] += $pedido;
				$totales['saldo'] += $saldo;
				$totales['cobranza'] += $cobranza;

				return $data;
			}

			return false;
		});


		$data['clientes'] = $data['clientes']->filter();
		$data['totales'] = $totales;

		// dd($data);
		$view = view( 'reportes.clientes.pdf' , $data );

		generateReport($view);
	}

	public function deudas()
	{
		return view('reportes.clientes.form');
	}

	public function mejoresClientes()
	{
    return view('reportes.mejores_clientes.form');
  }

	// public function mejoresClientesPdf(  $fecha_desde , $fecha_hasta, $local = "todos" )
	public function mejoresClientesPdf( ReporteMejorClienteRequest $request )
	{
    ob_clean();

    // @TODO refactorizar con el de ProductosMasVendidos que tambien es igual

    // $validator = $request->validate([
    //   'fecha_desde' => 'required|date',
    //   'fecha_hasta' => 'required|date|after_or_equal:fecha_hasta',
    //   'local' => 'required',
    // ]);

    // if ($validator->fails()) {
    //   return back()->withErrors($validator->errors());
    // } else {
    //   $fecha_desde_carbon = new Carbon($fecha_desde);
    //   $fecha_hasta_carbon = new Carbon($fecha_hasta);
    //   //  ---------
    //   $dias = config('app.reporte_mejores_clientes_dias_limite');
    //   if ($fecha_desde_carbon->addDays($dias)->isBefore($fecha_hasta_carbon)) {
    //     return back()->withErrors(['fecha_hasta' => "El lapso del reporte no puede superar los {$dias} dias"]);
    //   }
    // }

    $local_ = $request->local == 'todos' ? null : $request->local;
    $data_clientes = (new MejoresClientesReportData($request->fecha_desde, $request->fecha_hasta,$local_))->getData();

    $empresa = get_empresa();
		$data = [
			'clientes' => $data_clientes,
			'empresa_nombre' => $empresa->nombre(),
			'empresa_ruc' => $empresa->ruc(),
			'fecha_desde' => $request->fecha_desde,
			'fecha_hasta' => $request->fecha_hasta,
			'local' => $local_
		];

    $generator = new PDFGenerator(view('reportes.mejores_clientes.pdf',$data), PDFGenerator::HTMLGENERATOR );

    return $generator->generate();
	}
}