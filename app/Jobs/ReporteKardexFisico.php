<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;

class ReporteKardexFisico
{
  const FECHA_INICIO_BUSQUEDA = "1990-01-01";

  /**
   * Informaciòn del reporte
   * 
   * @param array
   */
  protected $data = [];

  public $local_id;
  public $fecha_inicio_busqueda;
  public $fecha_anterior_al_inicio;
  public $fecha_inicio;
  public $fecha_final;
  public $producto_id_inicio;
  public $producto_id_final;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct( $local_id, $fecha_inicio, $fecha_hasta , $articulo_desde, $articulo_hasta )
  {
    $this->local_id = $local_id;

    $this->fecha_inicio_busqueda = self::FECHA_INICIO_BUSQUEDA;
    
    
    $this->fecha_anterior_al_inicio = date('Y-m-d', strtotime('-1 day', strtotime($fecha_inicio)));
    
    $this->fecha_inicio = $fecha_inicio;
    $this->fecha_final = $fecha_hasta;

    $this->producto_id_inicio = $articulo_desde;
    $this->producto_id_final = $articulo_hasta;

    $this->handle();
  }


  public function getData()
  {
    return $this->data;
  }

  public function getQuery()
  {
    $local_operador = $this->local_id == "todos" ? '!=' : '=';

    return DB::connection('tenant')->table('guia_detalle')
      ->join('guias_cab', function ($join) {
        $join
          ->on('guias_cab.EmpCodi', '=', 'guia_detalle.empcodi')
          ->on('guias_cab.GuiOper', '=', 'guia_detalle.GuiOper');
      })
      ->join('productos', function ($join) {
        $join
          ->on('productos.ProCodi', '=', 'guia_detalle.DetCodi')
          ->on('productos.empcodi', '=', 'guia_detalle.empcodi');
      })
      ->join('unidad', function ($join) {
        $join
          ->on('unidad.UniCodi', '=', 'guia_detalle.UniCodi')
          ->on('unidad.empcodi', '=', 'guia_detalle.empcodi');
      })
      ->join('prov_clientes', function ($join) {
        $join
          ->on('prov_clientes.PCCodi', '=', 'guias_cab.PCCodi')
          ->on('prov_clientes.EmpCodi', '=', 'guias_cab.EmpCodi')
          ->on('prov_clientes.TipCodi', '=', 'guias_cab.TippCodi');
      })
      ->where('guias_cab.Loccodi', $local_operador, $this->local_id)
      ->whereBetween('guias_cab.GuiFemi', [ $this->fecha_inicio_busqueda , $this->fecha_final ])
      ->whereBetween('productos.ID', [ $this->producto_id_inicio , $this->producto_id_final ])
      ->orderBy('guias_cab.GuiFemi')
      ->select('guia_detalle.*', 'guias_cab.*', 'productos.ProNomb', 'productos.unpcodi', 'productos.ID', 'prov_clientes.PCNomb', 'unidad.*')
      ->get()
      ->unique('Linea')
      ->groupBy('DetCodi');

  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $data_products = $this->getQuery();
    $data = &$this->data;        
    $this->processData( $data_products, $data);
  }

  public function processData($data_products, &$data)
  {
    foreach( $data_products as $data_product ){
      $this->addToData( $data, $data_product );
      $productData = &$data[$data_product[0]->ID];
      $this->processProducto( $data_product, $productData );
    }
  }

  public function processProducto($data_product, &$productData )
  {
    $stock = $productData['stock_inicial']['saldo'];
    $totales = &$productData['totales'];
    $movimientos = &$productData['movimientos'];

    $data_product = $data_product->where('GuiFemi', '>=' , $this->fecha_inicio );

    foreach ( $data_product as $item ) {
      $this->processItem($item , $movimientos, $totales, $stock );
    }
  }

  public function processItem($item, &$movimientos, &$totales, &$stock )
  {
    $calculo_item = $this->getCalculoItem($item, $stock);
    $movimientos[] = $this->getInfoItem( $item, $calculo_item );

    # Agregar a los totales
    $totales['ingreso'] += $calculo_item['ingreso'];
    $totales['salida'] += $calculo_item['salida'];
    $totales['saldo'] = $stock = $calculo_item['saldo'];
    
  }

  public function getCalculoItem( $item , $saldo = null )
  {
    // $cantidad = (float) get_real_quantity($item->UniEnte, $item->UniMedi, $item->Detcant);
    $cantidad = (float) $item->DetFact * $item->Detcant;

    if ($item->EntSal == "I") {
      $ingreso = $cantidad;
      $salida = 0;
    }     
    else {
      $ingreso = 0;
      $salida = $cantidad;
    }

    if ( ! is_null($saldo) ) {
      $saldo = ((float) $saldo + $ingreso) - $salida;
    }

    return [
      'ingreso' => $ingreso,
      'salida' => $salida,
      'saldo' => $saldo
    ];
  }

  public function getInfoItem($item, $calculo_item)
  {
    return [
      'fecha' => $item->GuiFemi,
      'motivo' => $item->TmoCodi,
      'almacen' => $item->Loccodi,
      'razon_social' => $item->PCNomb,
      'nro_operacion' => $item->GuiOper,
      'documento_referencia' => $item->docrefe,
      'ingreso' => $calculo_item['ingreso'],
      'salida' => $calculo_item['salida'],
      'saldo' => $calculo_item['saldo'],
    ];
  }

  public function getInfoProducto($data_product)
  {
    return [
        'id' => $data_product->ID,
        'codigo' => $data_product->DetCodi,
        'nombre' => $data_product->ProNomb,
        'unidad' => $data_product->unpcodi,
    ];
  }


  public function getTotalesStockInicial($items)
  {
    $salida = 0;
    $ingreso = 0;

    foreach ( $items as $item ) {
      $stock = null;
      $calculo = $this->getCalculoItem($item, $stock );
      $salida += $calculo['salida'];
      $ingreso += $calculo['ingreso'];
    }

    return [
      'salida' => $salida,
      'ingreso' => $ingreso,
      'saldo' => $ingreso - $salida,
    ];
  }

  public function getStockInicialProducto($data_product)
  {
    $salida = 0;
    $ingreso = 0;
    $items_anteriores = $data_product->where('GuiFemi', '<=', $this->fecha_anterior_al_inicio );

    if( $items_anteriores->count() ){
      return $this->getTotalesStockInicial( $items_anteriores, []);
    }
    else {
      return [
        'salida' => 0,
        'ingreso' => 0,
        'saldo' => 0
      ];
    }
  }  

  public function addToData( &$arrData, $data_product )
  {
    $stock_incial = $this->getStockInicialProducto($data_product);

    $info_producto = [
      'info' =>  $this->getInfoProducto( $data_product[0] ),
      'stock_inicial' => $stock_incial,
      'totales' => $stock_incial,
      'movimientos' => []
    ];

    $arrData[ $data_product[0]->ID ] = $info_producto;
  }

}
