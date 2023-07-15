<?php

namespace App\ReportData;

use App\Local;
use App\Moneda;
use Illuminate\Support\Facades\DB;

class InventarioValorizadoReportData
{
  public $data;
  public $tipo_existencia;
  public $conStock;
  public $campo_stock;
  public $campo_costo;
  public $total_general;
  
  public function __construct($local, $moneda, $tipo_existencia)
  {
    $this->tipo_existencia = $tipo_existencia;
    $this->conStock = true;
    $this->campo_stock = 'prosto' . substr( $local , -1 );
    // $this->campo_costo = $moneda == Moneda::DOLAR_ID ? 'ProPUCD' : 'ProPUCS';
    $this->campo_costo = $moneda == Moneda::DOLAR_ID ? 'UniPUCD' : 'UniPUCS';
    $this->lista_precio = Local::find($local)->listas->first()->LisCodi;
    $this->processQuery();
  }

  public function getQuery()
  {
    $lista_precio = $this->lista_precio;

    $query = DB::connection('tenant')->table('productos')
      ->join('marca', function ($join) {
        $join->on('marca.MarCodi', '=', 'productos.marcodi');
      })
      ->leftJoin('grupos', function ($join) {
        $join->on('grupos.GruCodi', '=', 'productos.grucodi');
      })
      ->join('unidad', function ($join) use ($lista_precio) {
          $join
          ->on('unidad.Id', '=', 'productos.ID')
          ->where('unidad.LisCodi' , '=' , $lista_precio)
          ->where('unidad.UniEnte', '=', "1")
          ->where('unidad.UniMedi', '=', "1");
      });

      if($this->tipo_existencia){
        $query->where('productos.tiecodi', $this->tipo_existencia );
      }

      if($this->conStock){
        $query->where("productos.{$this->campo_stock}", '<>', 0 );
      }

      if($this->tipo_existencia){
        $query->where('tiecodi', $this->tipo_existencia );
      }

      return $query->select([
        'productos.grucodi as grupo_codigo',
        'grupos.GruNomb as grupo_nombre',
        'productos.ProCodi as id',
        'productos.unpcodi as unidad',
        'productos.ProNomb as nombre',
        'productos.ProPeso as peso',
        'marca.MarNomb as marca',
        'unidad.UniCodi as unidad_id',
        'unidad.LisCodi as lista-precio',
        "productos.{$this->campo_stock} as stock",
        "unidad.{$this->campo_costo} as costo",
      ])
      ->orderBy('grupo_codigo')
      ->get()
      ->groupBy('grupo_codigo');
  }


  public function getData()
  {
    return $this->data;
  }

  public function setData( $data )
  {
    return $this->data = $data;
  }

  /**
   * Procesar grupo
   * 
   */
  public function processGroup( &$data_report, $productos )
  {
    $data_group = [
      'products_group' => [],
      'info_group' => [],
    ];

    $total_grupo = 0;

    foreach( $productos as $producto ){

      $importe = $producto->costo * $producto->stock;
      $total_grupo += $importe;

      $data_group['products_group'][] = [
        'nombre' => $producto->nombre,  
        'unidad' => $producto->unidad,  
        'id' => $producto->id,  
        'stock' => fixedValue($producto->stock),
        'peso' => fixedValue($producto->peso),
        'costo' => fixedValue($producto->costo), 
        'marca' => $producto->marca,
        'importe' => fixedValue($importe)
      ];

      $this->total_general += $importe;
    }

    // Obtener 
    $producto = $productos->first();
    
    $data_group['info_group'] = [
      'codigo' => $producto->grupo_codigo ,
      'nombre' => $producto->grupo_nombre,
      'total' => fixedValue($total_grupo),
    ];

    array_push( $data_report , $data_group );
  }

  public function processQuery()
  {
    $data_groups = $this->getQuery();
    $data_report = [];
        
    foreach( $data_groups->chunk(5) as $groups ){
      foreach( $groups as $productos ){
        $this->processGroup( $data_report, $productos);
      }
    }
    $this->setData($data_report);
  }

}
