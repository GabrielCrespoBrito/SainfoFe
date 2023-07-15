<?php

namespace App\Jobs\Reporte;

use App\Producto;
use Carbon\Carbon;
use App\GuiaSalida;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReporteKardexFecha
{
	protected $data = [
		'success' => false,
		'items' => []
	];

	protected $empresa;
	protected $fecha_inicio;
	protected $fecha_final;
	protected $local;

	protected $stock_conteo = [];

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($fecha_inicio, $fecha_final, $local)
	{
		$this->fecha_inicio = $fecha_inicio;
		$this->fecha_final = $fecha_final;
		$this->empresa = get_empresa();
		$this->local = $local;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function getQuery()
	{
		$query = DB::connection('tenant')->table('guia_detalle')
			->join('guias_cab', function ($join) {
				$join
					->on('guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
					->on('guias_cab.EmpCodi', '=', 'guia_detalle.empcodi');
			})
			->join('productos', function ($join) {
				$join
					->on('productos.ProCodi', '=', 'guia_detalle.DetCodi')
					->on('productos.empcodi', '=', 'guias_cab.EmpCodi');
			})
			->whereBetween('guias_cab.GuiFemi', [$this->fecha_inicio, $this->fecha_final]);

		if ($this->local != null) {
			$query->where('guias_cab.Loccodi', $this->local);
		}

		return $query
			->orderBy('guias_cab.GuiFemi')
			->select(
			'guia_detalle.DetCodi as codigo_producto',
			'guia_detalle.DetFact as factor',
			'guia_detalle.Detcant as cantidad',
			'guias_cab.EntSal as tipo_mov',
			'guias_cab.GuiFemi as fecha',
			'guias_cab.Loccodi as local_id',
			'productos.ProNomb as nombre_producto',
			'productos.ProPUCD as costo_dolar',
			'productos.ProPUCS as costo_soles',
			'productos.ID as codigo',
			'productos.ProPeso as peso',
			'productos.unpcodi as unidad_nombre'
		);
	}

	public function getQueryForStockInicial($fecha, $producto_id, $local_id)
	{
		$fechaCarbon = new Carbon($fecha);
    
		return DB::connection('tenant')->table('guia_detalle')
			->join('guias_cab', function ($join) {
				$join
					->on('guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
					->on('guias_cab.EmpCodi', '=', 'guia_detalle.empcodi');
			})
			->whereBetween('guias_cab.GuiFemi', [ '0001-01-01' , $fechaCarbon->subDays(1) ])
			->where('guia_detalle.DetCodi' , '=', $producto_id  )
			->where('guias_cab.Loccodi' , '=', $local_id  )
			->select(
			'guia_detalle.DetFact as factor',
			'guia_detalle.Detcant as cantidad',
			'guias_cab.EntSal as tipo_mov',
			)->get();
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$query =  $this->getQuery()->get()->groupBy(['fecha', 'codigo_producto', 'local_id']);

		$this->processDays($query);
	}

	/**
	 * Procesar dias
	 * 
	 */
	public function processDays($query)
	{
		foreach( $query as $fecha_id => $products ) 
		{
			$this->processProducts( $fecha_id , $products);
		}
	}

	/**
	 * Procesar dias
	 * 
	 */
	public function processProducts( $fecha_id, $products)
	{
		foreach( $products as $product_id => $locals ) 
		{
			$this->processLocalsProduct( $fecha_id,  $product_id, $locals);
		}
	}

	/**
	 * Procesar dias
	 * 
	 */
	public function processLocalsProduct( $fecha_id, $producto_id, $locals )
	{
		foreach( $locals as $local_id => $local_products ) 
		{
			$this->processItem( $fecha_id , $producto_id, $local_id, $local_products);
		}
	}

	/**
	 * Procesar dias
	 * 
	 */
	public function processItem( $fecha_id, $product_id, $local_id, $local_products)
	{
		$item = $local_products->first();

		$info_producto  = $this->getTotalsValue($fecha_id, $product_id, $local_id, $item, $local_products); 

		$this->addToData( $item->fecha,  $item->codigo_producto, $item->nombre_producto, $item->peso, $item->unidad_nombre, $item->local_id, $info_producto->stock_inicial, $info_producto->stock_actual, $info_producto->costo_inicial, $info_producto->nombre_proveedor, $info_producto->ingreso, $info_producto->salida  );
	}


	public function searchStockInicial($fecha,$producto_id,$local_id)
	{
		return $this->getCantidad($this->getQueryForStockInicial($fecha,$producto_id,$local_id));
	}

	public function getStocks( $fecha, $producto_id, $local_id, $cantidad )
	{
		$id_stock = $producto_id . $local_id; 


		// Si existe
		if(array_key_exists( $id_stock, $this->stock_conteo )){
			$stock_inicial = $this->stock_conteo[$id_stock];
			$stock_actual = $this->stock_conteo[$id_stock] += $cantidad;
		}

		// Agregar al array de conteos el conteo inicial actual
		else {
			$stock_inicial = $this->stock_conteo[$id_stock] = $this->searchStockInicial( $fecha, $producto_id, $local_id )['cantidad'];
			$stock_actual = $this->stock_conteo[$id_stock] += $cantidad;
		}

		return (object) [
			'inicial' => $stock_inicial,
			'actual' => $stock_actual
		];
	}


	public function getCantidad( $local_products )
	{
    $cantidad = 0;
		$ingreso = 0;
    $salida = 0;

		foreach( $local_products as $detalle )
		{
      $cantidad_real = $detalle->cantidad * $detalle->factor;

			if( $detalle->tipo_mov == GuiaSalida::INGRESO ){
				$cantidad += $cantidad_real;
        $ingreso += $cantidad_real;
			}
			else {
				$cantidad -= $cantidad_real;
        $salida += $cantidad_real;
			}
		}

		// return $cantidad;
    return [
      'cantidad' => $cantidad,
      'ingreso' => $ingreso,
      'salida' => $salida,
    ];
	}

	public function getTotalsValue( $fecha_id, $producto_id, $local_id, $item, $local_products )
	{
    $cantidades = $this->getCantidad($local_products);
    $cantidad_actual =  $cantidades['cantidad'];
		$stocks =  $this->getStocks( $fecha_id, $producto_id, $local_id , $cantidad_actual );
		$stock_inicial = $stocks->inicial;
		$ingreso = $cantidades['ingreso'];
    $salida = $cantidades['salida'];
		$stock_actual = $stocks->actual;
		$cantidad = 1;		
		$nombre_proveedor = '';
		$data_costo_producto = Producto::getProductCostos(  $producto_id, $fecha_id, $local_id, $cantidad, $item->costo_dolar, $item->costo_soles, true );

		$costo_inicial = $data_costo_producto['01'];
		
		if( $data_costo_producto['ultima_compra'] ){
			$nombre_proveedor = $data_costo_producto['ultima_compra']->PCNomb;
		}

		return (object) [
		'stock_inicial' =>  $stock_inicial,
		'stock_actual' =>  $stock_actual,
		'ingreso' =>  $ingreso,
    'salida' =>  $salida,
		'costo_inicial' =>  $costo_inicial,
		'nombre_proveedor' =>  $nombre_proveedor,
		];
	}

	public function addToData(
		$fecha_id, 
		$producto_id, 
		$descripcion, 
		$peso, 
		$unidad, 
		$almacen, 
		$stock_inicial, 
		$stock_actual, 
		$costo_inicial, 
		$nombre_proveedor,
    $ingreso = 0,
    $salida = 0 )
	{
		$this->data['items'][] = [
			'fecha_id' => $fecha_id, 
			'year' =>  substr($fecha_id,0,4), 
			'producto_id' => $producto_id, 
			'descripcion' => $descripcion, 
			'peso' => $peso, 
			'unidad' => $unidad, 
			'almacen' => $almacen, 
			'stock_inicial' => $stock_inicial,
			'ingreso' => $ingreso,
      'salida' => $salida, 
			'stock_actual' => $stock_actual, 
			'costo_inicial' => $costo_inicial, 
			'nombre_proveedor' => $nombre_proveedor
		];
	}

	public function getData()
	{
		return $this->data;
	}
}