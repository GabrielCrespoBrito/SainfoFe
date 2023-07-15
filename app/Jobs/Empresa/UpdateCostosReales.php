<?php

namespace App\Jobs\Empresa;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\Producto\GetLastCostos;


class UpdateCostosReales
{
  protected $calculos = [

  ];

  public function getQuery()
  {
    return DB::connection('tenant')->table('ventas_detalle')
      ->join('ventas_cab' , 'ventas_cab.VtaOper' , "=" , 'ventas_detalle.VtaOper')
      ->join('unidad', 'unidad.Unicodi', "=", 'ventas_detalle.UniCodi')
      ->join('productos', 'ventas_detalle.DetCodi', "=", 'productos.ProCodi')
      ->select([
        "ventas_cab.VtaFvta as fecha_emision",
        "ventas_cab.LocCodi as local",
        "ventas_detalle.Linea as id",
        "ventas_detalle.Detcant as cantidad",
        "ventas_detalle.DetCodi as producto_id",
        "ventas_detalle.UniCodi as unidad_id",
        "ventas_detalle.Detfact as factor_venta",
        "productos.incluye_igv as incluye_igv",
        "unidad.UniEnte as entero",
        "unidad.UniMedi as medida",
        "unidad.UniPUCD as unidad_costo_dolar",
        "unidad.UniPUCS as unidad_costo_soles"
      ])
      ->get()
      ->chunk(100);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {

    $detalles_chunk = $this->getQuery();
    foreach ($detalles_chunk as $detalles) {
      foreach ($detalles as $detalle) {
        $costos = $this->getCostosDetalle( $detalle );
        DB::connection('tenant')->table('ventas_detalle')
          ->where('Linea', '=',  $detalle->id)
          ->update([
            "DetCSol" => $costos->sol,
            "DetCDol" => $costos->dolar,
          ]);
      }
    }
  }

  public function getCostosDetalle($detalle)
  {
    $unidad_factor = $detalle->entero / $detalle->medida;
    return  (new GetLastCostos(
      $detalle->producto_id,
      $detalle->unidad_costo_soles,
      $detalle->unidad_costo_dolar,
      $unidad_factor,
      $detalle->fecha_emision,
      $detalle->local,
      $detalle->cantidad,
      $detalle->factor_venta,
      $detalle->incluye_igv,
      true
    ))->handle();

  }

}
