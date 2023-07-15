<?php

namespace App\Jobs\Reporte;

use App\Moneda;
use App\TipoDocumentoPago;
use Illuminate\Support\Facades\DB;

class ReporteCompraVentaProducto
{
	/**
   * Data del reporte
  */
  protected $data = [
    'success' => false,
    'docs' => [],
    'totals' => [
      'total_cantidad' => 0,
      'ultimo_costo' => 0,
      'costo_promedio' => 0,
      'total_precio' => 0,

		],
  ];

	protected $id_producto;
	protected $search_ventas;
	protected $fecha_desde;
	protected $fecha_hasta;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($id_producto, bool $search_ventas, $fecha_desde, $fecha_hasta)
	{
		$this->id_producto = $id_producto;
		$this->search_ventas = $search_ventas;
		$this->fecha_desde = $fecha_desde;
		$this->fecha_hasta = $fecha_hasta;
		//
	}


	public function getQueryVentas()
	{
		return DB::connection('tenant')->table('ventas_detalle')
			->join('ventas_cab', function ($join) {
				$join->on('ventas_cab.VtaOper', '=', 'ventas_detalle.VtaOper');
			})
			->join('prov_clientes', function ($join) {
				$join
					->on('prov_clientes.PCCodi', '=', 'ventas_cab.PCCodi')
					->where('prov_clientes.TipCodi', '=', 'C');
			})
			->join('moneda', 'moneda.moncodi', '=', 'ventas_cab.MonCodi')
			->where('ventas_detalle.DetCodi', '=', $this->id_producto)
      ->whereIn('ventas_cab.TidCodi', [ TipoDocumentoPago::FACTURA, TipoDocumentoPago::BOLETA, TipoDocumentoPago::NOTA_CREDITO, TipoDocumentoPago::NOTA_DEBITO, TipoDocumentoPago::NOTA_VENTA ])
			->whereBetween('ventas_cab.VtaFvta', [ $this->fecha_desde, $this->fecha_hasta ] )
			->select(
				'ventas_cab.VtaFvta as fecha',
				'ventas_cab.TidCodi as tipo_documento',
				'ventas_cab.VtaOper as numero_operacion',
				'ventas_cab.VtaSeri as serie',
				'ventas_cab.VtaNumee as numero',
				'ventas_cab.VtaTcam as tipo_cambio',
				'ventas_detalle.DetUnid as unidad',
				'ventas_detalle.DetCant as cantidad',
				'ventas_detalle.DetPrec as precio',
				'ventas_detalle.Detfact as factor',
				'ventas_detalle.DetCSol as importe_soles',
				'ventas_detalle.DetCDol as importe_dolar',
				'prov_clientes.PCNomb as cliente_nombre',
				'prov_clientes.PCRucc as cliente_documento',
				'ventas_cab.MonCodi as moneda_codigo',
				'moneda.monabre as moneda_abbreviatura'
			)->get();
	}


	public function getQueryCompras()
	{
		return DB::connection('tenant')->table('compras_detalle')
			->join('compras_cab', function ($join) {
				$join->on('compras_cab.CpaOper', '=', 'compras_detalle.CpaOper');
			})
			->join('prov_clientes', function ($join) {
				$join
					->on('prov_clientes.PCCodi', '=', 'compras_cab.PCcodi')
					->where('prov_clientes.TipCodi', '=', 'P');
			})
			->join('moneda', 'moneda.moncodi', '=', 'compras_cab.moncodi')
			->where('compras_detalle.Detcodi', '=', $this->id_producto )
			->whereBetween('compras_cab.CpaFCon', [ $this->fecha_desde, $this->fecha_hasta ] )
			->select(
				'compras_cab.CpaFCpa as fecha',
				'compras_cab.TidCodi as tipo_documento',
				'compras_cab.CpaOper as numero_operacion',
				'compras_cab.CpaSerie as serie',
				'compras_cab.CpaNumee as numero',
				'compras_cab.CpaTCam as tipo_cambio',
				'compras_detalle.DetUnid as unidad',
				'compras_detalle.DetCant as cantidad',
				'compras_detalle.DetPrec as precio',
				'compras_detalle.Detfact as factor',
				'compras_detalle.DetCSol as importe_soles',
				'compras_detalle.DetCDol as importe_dolar',
				'prov_clientes.PCNomb as cliente_nombre',
				'prov_clientes.PCRucc as cliente_documento',
				'compras_cab.moncodi as moneda_codigo',
				'moneda.monabre as moneda_abbreviatura'
			)->get();
	}


	public function getQuery()
	{
		return $this->search_ventas ? $this->getQueryVentas() : $this->getQueryCompras(); 
	}


	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$items = $this->getQuery();

		$route = $this->search_ventas ? 'ventas.show' : 'compras.show';

		foreach( $items as &$item ){
			$this->data['totals']['total_cantidad'] += $item->cantidad * $item->factor;
			$this->data['totals']['total_precio'] += $items->sum('importe_soles');			
			$item->route = route( $route, $item->numero_operacion);
			$item->correlativo = $item->serie . '-' . $item->numero;
		}

		$this->data['docs'] = $items;
		$this->data['success'] = (bool) count($items);
	}

	public function getData()
	{
		return $this->data;
	}
}
