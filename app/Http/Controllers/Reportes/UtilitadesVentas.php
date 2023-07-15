<?php 

namespace App\Http\Controllers\Reportes;

use App\Compra;
use App\Venta;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use mikehaertl\wkhtmlto\Pdf as WKPDF;

class UtilidadesVentas
{
	const TITULO_DETALLADO = "DETALLE DE UTILIDADES POR FECHA";
	const TITULO_RESUMEN = "RESUMEN DE UTILIDADES POR FECHA";

	public $fecha_desde;
	public $fecha_hasta;
	public $is_resumen;


	public function __construct($fecha_desde, $fecha_hasta, $is_resumen = true ){
		$this->fecha_desde = $fecha_desde;
		$this->fecha_hasta = $fecha_hasta;
		$this->is_resumen  = $is_resumen;
	}

	public function make()
  {
		$data =  DB::connection('tenant')->table('ventas_detalle')
		->join('ventas_cab' , function($join){
		  $join
		  ->on('ventas_cab.VtaOper', '=', 'ventas_detalle.VtaOper' )
		  ->on('ventas_cab.EmpCodi', '=', 'ventas_detalle.EmpCodi' );
		})
		->join('prov_clientes' , function($join){
		  $join
		  ->on('prov_clientes.PCCodi', '=', 'ventas_cab.PCCodi' )
		  ->on('prov_clientes.EmpCodi', '=', 'ventas_cab.EmpCodi' )
		  ->where('prov_clientes.TipCodi', '=', 'C' );
		})	
		->join('productos' , function($join){
		  $join
		  ->on('productos.ProCodi', '=', 'ventas_detalle.DetCodi' )
		  ->on('productos.empcodi', '=', 'ventas_detalle.EmpCodi' );
		})   		
		->where('ventas_detalle.EmpCodi' , '=' , empcodi())
    ->whereNotIn('ventas_cab.TidCodi', ['52'] )
		->whereBetWeen('ventas_cab.VtaFvta' , [ $this->fecha_desde , $this->fecha_hasta ])
		
		->select([
			'ventas_detalle.DetImpo',			
			'ventas_cab.VtaFvta',
			'ventas_cab.VtaNume',			
			'ventas_cab.VtaImpo',			
			'ventas_detalle.DetCant',
			'productos.ProPUCS',			
			'prov_clientes.PCNomb',
		])
		->orderBy('ventas_cab.VtaFvta', 'asc')
		->orderBy('ventas_cab.VtaNume', 'asc');

		// ->get()
		// ->groupBy('VtaFvta');

		if( $this->is_resumen ){
			return $data
			->get()
			->groupBy('VtaFvta');
		}		
		else {
			return $data
			->get()
			->groupBy('VtaNume');
		}


	}

	public function streampdf(){

		$data = $this->make(); 

		$titulo =  $this->is_resumen ? self::TITULO_RESUMEN : self::TITULO_DETALLADO;

		$view =  view( 'reportes.utilidades_ventas.pdf' , [
			'fecha_desde' => $this->fecha_desde,
			'fecha_hasta' => $this->fecha_hasta,
			'is_detallado' => ! $this->is_resumen,
			'titulo' => $titulo,
			'data' => $data 
		]);

		$pdf = new WKPDF([
		  'commandOptions' => [
		  'useExec' => true,
		  'escapeArgs' => false,
		  'locale' => 'es_ES.UTF-8',
		  'procOptions' => [
				'bypass_shell' => true,
				'suppress_errors' => true,
				],
		  ],
		]);

		$pdf->binary = getBinaryPdf();

		$globalOptions = [ 'no-outline', 'page-size' => 'Letter' ];
		$pdf->setOptions($globalOptions);
		$pdf->addPage($view);
		if (!$pdf->send()) {
		  throw new \Exception('Could not create PDF: '.$pdf->getError());
		}
	}
	
}