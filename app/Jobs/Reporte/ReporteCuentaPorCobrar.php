<?php

namespace App\Jobs\Reporte;

class ReporteCuentaPorCobrar
{
	protected $data = [
		'docs' => [],
		'total' => [
			'01' => 0,
			'02' => 0,
		],
	];

	protected $current_fecha;
	protected $fecha_desde;
	protected $fecha_hasta;
	protected $local;
	protected $docs;
	protected $agrupacionByCliente;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($docs, bool $agrupacionByCliente, $campos)
	{
		$this->docs = $docs;
		$this->campos = $campos;
		$this->agrupacionByCliente = $agrupacionByCliente;
		$this->handle();
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$query =  $this->docs;

		$docs = [];
		$data = [];
		$total_reporte = ['01' => 0, '02' => 0];

		// Iterar por grupo de cliente
		if ($this->agrupacionByCliente) {

			foreach ($query as $codigoCliente => $ventas) {

				$data[$codigoCliente] = [
					'info' => [],
					'docs' => [],
					'total' => ['01' => 0, '02' => 0],
				];

				$cliente_info = $ventas->first()->cliente_with; 
				$cliente_text = sprintf('%s %s %s', $codigoCliente , $cliente_info->PCRucc, $cliente_info->PCNomb );
				// info
				$data[$codigoCliente]['info'] = $cliente_text;
				// docs
				$docs = &$data[$codigoCliente]['docs'];
				// total
				$total_cliente = &$data[$codigoCliente]['total'];

				foreach ($ventas as $venta) {
					$this->processVenta($venta, $docs, $total_reporte, $total_cliente);
				}
				$this->data['docs'] = $data;
			}
		}

		// Iterar simplemente por ventas
		else {
			foreach ($query as $venta) {
				$total_cliente = null;
				$this->processVenta($venta, $docs, $total_reporte, $total_cliente);
			}
			$this->data['docs'] = $docs;
		}
		
		$this->data['total'] = $total_reporte;
	}

	/**
	 * Procesar la venta especifica
	 * 
	 */
	public function processVenta($venta, &$arrAdd, &$total_reporte, &$total_cliente = null)
	{
		$arrAdd[] = $this->getInfoItem($venta);

		// Agregar al total
		$isSol = $venta->isSol();
    $total = $venta->{$this->campos['saldo']};
		$isNC = $venta->isNotaCredito();
		$totales = [];

		if ($isNC) {
			$totales['01'] = $isSol ? convertNegative($total) : 0;
			$totales['02'] = $isSol ? 0 : convertNegative($total);
		} else {
			$totales['01'] = $isSol ? $total : 0;
			$totales['02'] = $isSol ? 0 : $total;
		}

		$this->addToTotal($total_reporte, $totales);

		if ($total_cliente !== null ) {
			$this->addToTotal($total_cliente, $totales);
		}
	}


	public function addToTotal(&$total, $total_add)
	{
		$total['01'] += $total_add['01'];
		$total['02'] += $total_add['02'];
	}

	/**
	 * Establecer la data del reporte
	 * 
	 * @return array
	 */
	public function addToData(&$arrAdd, $info = [], $addIndexItems = true)
	{
		// Info
		$arrAdd['info'] = $info;

		// Items
		if ($addIndexItems) {
			$arrAdd['items'] = [];
		}

		// Total
		$arrAdd['total'] = [
			'costo_soles' => 0,
			'costo_dolar' => 0,
			'venta_soles' => 0,
			'venta_dolar' => 0,
			'utilidad_soles' => 0,
			'utilidad_dolar' => 0,
		];

		return $arrAdd;
	}

	/**
	 * Establecer la informaciòn del item
	 * 
	 * @return array
	 */
	public function getInfoItem($venta)
	{
		$total = $venta->{$this->campos['total']};
		$pago = $venta->{$this->campos['pago']};
		$saldo = $venta->{$this->campos['saldo']};


		if ($venta->isNotaCredito()) {
			$total = convertNegative($total);
			$pago = convertNegative($pago);
			$saldo = convertNegative($saldo);
		}

		return [
			'fecha_cpa' => $venta->{$this->campos['fecha']},
			'fecha_cpa2' => $venta->{$this->campos['fecha_ven']},
			'ruc' => $venta->cliente_with->PCRucc,
			'razon_social' => $venta->cliente_with->PCNomb,
			'numoper' => $venta->{$this->campos['id']},
			'tidcodi' => $venta->{$this->campos['tidcodi']},
			'correlativo' => $venta->{$this->campos['nume']},
			'moneda' => $venta->moneda->monabre,
			'total' => deci($total),
			'pagado' => deci($pago),
			'saldo' => deci($saldo),
		];
	}


	/**
	 * Establecer la informaciòn del documento
	 * 
	 * @return array
	 */
	public function getInfoVenta($venta)
	{
		return [
			'data' => $venta->VtaNume,
			'cliente' => $venta->cliente_with->PCNomb,
			'cliente_ruc' => $venta->cliente_with->PCRucc,
			'count' => count($venta->items)
		];
	}

	/**
	 * Establecer la informaciòn del item
	 * 
	 * @return array
	 */
	public function setPositiveUtilidad(&$data)
	{
		$data['info']['positive'] = is_positive($data['total']['utilidad_soles']);
	}

	/**
	 * Obtener la data del reporte
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
}