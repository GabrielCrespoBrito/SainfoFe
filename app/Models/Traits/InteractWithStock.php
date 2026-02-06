<?php

namespace App\Models\Traits;

use App\TipoMovimiento;
use Illuminate\Support\Facades\DB;

/**
 * Interaccionar con el inventario
 * 
 */
trait InteractWithStock
{
  public function updateStock($procodi,  $local, $fecha = null)
  {
    self::updateStockStatic($procodi, $local, $fecha);
  }

  public static function updateStockStatic($procodi, $local, $fecha = null)
  {
    $query = DB::connection('tenant')->table('guia_detalle')
      ->join('guias_cab', 'guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
      ->where('guia_detalle.DetCodi', '=', $procodi)
      ->where('guias_cab.Loccodi', '=', $local);

    if ($fecha) {
      $query->where('guias_cab.GuiFemi', '<=', $fecha);
    }

    $total = $query->sum('CpaVtaCant');
    $campo_stock = (new static)->getCampoLocal($local);
    DB::connection('tenant')->table('productos')
      ->where('ProCodi', '=', $procodi)
      ->update([ $campo_stock => $total ]);
  }

  /**
   * Actualizar el Stock Total de un producto
   * 
   */
  public static function updateStock2( $procodi )
  {
    // Excluir los movimientos de orden de compra
    $stocksTotalByLocal = DB::connection('tenant')->table('guia_detalle')
    ->join('guias_cab', 'guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
    ->select('guias_cab.Loccodi', DB::raw('SUM(guia_detalle.CpaVtaCant) as cant'))
    ->where('guia_detalle.DetCodi', '=', $procodi)
    ->where('guias_cab.TmoCodi', '!=', TipoMovimiento::INVENTARIO_ORDENCOMPRA)
    ->groupBy('guias_cab.Loccodi')
    ->get();

    $stocksTotales = [
      'prosto1' => 0,
      'prosto2' => 0,
      'prosto3' => 0,
      'prosto4' => 0,
      'prosto5' => 0,
      'prosto6' => 0,
      'prosto7' => 0,
      'prosto8' => 0,
      'prosto9' => 0
    ];

    foreach ($stocksTotalByLocal as $stockTotal ) {
      $nombre = 'prosto' . (int) $stockTotal->Loccodi;
      $stocksTotales[$nombre] = $stockTotal->cant;
    }

    DB::connection('tenant')->table('productos')
    ->where('ProCodi', '=', $procodi)
    ->update($stocksTotales);

    return $stocksTotales;
  }

  public function updateAllStock($procodi, $fecha = null)
  {
    $locales = ['001', '002', '003', '004', '005', '006', '007', '008', '009', '010'];
    foreach ($locales as $local) {
      $this->updateStock($procodi, $local, $fecha);
    }
  }

  public function getCampoLocal($loccodi)
  {
    return 'prosto' .  get_number_local($loccodi);
  }
}
