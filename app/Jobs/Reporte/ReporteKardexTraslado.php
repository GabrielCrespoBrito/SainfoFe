<?php

namespace App\Jobs\Reporte;

use App\MotivoTraslado;
use Illuminate\Support\Facades\DB;

class ReporteKardexTraslado
{
  protected $data;

  public function __construct($fecha_inicio, $fecha_final, $local_origen, $local_destino)
  {
    $this->fecha_inicio = $fecha_inicio;
    $this->fecha_final = $fecha_final;
    $this->local_origen = $local_origen;
    $this->local_destino = $local_destino;
  }

  public function getData()
  {
    return $this->data;
  }

  public function setData($data)
  {
    $this->data = $data;
    return $this;
  }

  public function getQuery()
  {
    return DB::connection('tenant')->table('guia_detalle')
      ->join('guias_cab', function ($join) {
        $join
          ->on('guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
          ->on('guias_cab.EmpCodi', '=', 'guia_detalle.empcodi');
      })
      ->join('guias_cab as guias_cab_destino', function ($join) {
        $join->on('guias_cab.CtoOper', '=', 'guias_cab_destino.GuiOper');
      })
      ->join('productos', function ($join) {
        $join->on('productos.ProCodi', '=', 'guia_detalle.DetCodi');
      })
      ->whereBetween('guias_cab.GuiFemi', [$this->fecha_inicio, $this->fecha_final])
      ->where('guias_cab.motcodi', MotivoTraslado::TRASLADO_MISMA_EMPRESA )
      ->where('guias_cab.Loccodi', $this->local_origen )
      ->where('guias_cab_destino.Loccodi', $this->local_destino )
      ->orderBy('guias_cab.GuiFemi')
      ->select(

        'guia_detalle.DetCodi as codigo_producto',
        'guia_detalle.DetFact as factor',
        'guia_detalle.Detcant as cantidad',
        // 'guias_cab.EntSal as tipo_mov',
        // ------------------------------------------------------------------------------------------------------------------------
        'guias_cab_destino.GuiOper as id_guia_destino',
        'guias_cab_destino.GuiSeri as serie_guia_destino',
        'guias_cab_destino.GuiFemi as fecha_guia_destino',
        'guias_cab_destino.GuiNumee as numero_guia_destino',

      // -----------------------------------------------------------------------------------------------------------------------        
        'guias_cab.GuiOper as id_guia_origen',
        'guias_cab.GuiSeri as serie_guia_origen',
        'guias_cab.GuiNumee as numero_guia_origen',
        'guias_cab.GuiFemi as fecha_guia_origen',
        // 'guias_cab.Loccodi as local_id',
        'productos.ProNomb as nombre_producto',
        // 'productos.ProPUCD as costo_dolar',
        // 'productos.ProPUCS as costo_soles',
        // 'productos.ID as codigo',
        'productos.ProPeso as peso',
        'productos.unpcodi as unidad_nombre')
      ->get()
      ->groupBy('id_guia_origen');
  }

  public function handle()
  {
    $this->data = $this->processQuery($this->getQuery());
  }

  public function processQuery( $query )
  {
    $data = [];

    foreach( $query as $guia_origen_id =>  $products_traslado ){


      $data_first = $products_traslado->first();

      $data[$guia_origen_id] = [
        'guia_origen'  => [
          'id_guia_origen' => $data_first->id_guia_origen,
          'serie_guia_origen' => $data_first->serie_guia_origen,
          'numero_guia_origen' => $data_first->numero_guia_origen,
          'fecha_guia_origen' => $data_first->fecha_guia_origen,
        ],
        'guia_destino' => [
          'id_guia_destino' => $data_first->id_guia_destino,
          'serie_guia_destino' => $data_first->serie_guia_destino,
          'numero_guia_destino' => $data_first->numero_guia_destino,
          'fecha_guia_destino' => $data_first->fecha_guia_destino,
        ],

        'items' => []
      ];
      
      foreach ($products_traslado as $producto ){
        $data_producto = (array) $producto;
        $data_producto['cantidad'] = decimal($producto->factor * $producto->cantidad, 4);
        $data_producto['peso'] = decimal($producto->peso , 4);

        array_push( $data[$guia_origen_id]['items'] , $data_producto );
      }
    }

    return $data;
  }


}