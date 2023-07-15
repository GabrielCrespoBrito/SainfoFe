<?php

namespace App\Models\Traits;

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
      // 
      if ($fecha) {
      $query->where('guias_cab.GuiFemi', '<=', $fecha);
    }
    $total = $query->sum('CpaVtaCant');
    $campo_stock = (new static)->getCampoLocal($local);
    DB::connection('tenant')->table('productos')
      ->where('ProCodi', '=', $procodi)
      ->update([
        $campo_stock => $total
    ]);
  }

  public function updateAllStock($procodi, $fecha = null)
  {
    $locales = [ '001' , '002' , '003' , '004' , '005' , '006' , '007' , '008' , '009' , '010' ];
    foreach ($locales as $local) {
      $this->updateStock($procodi, $local, $fecha);
    }
  }

  public function getCampoLocal($loccodi)
  {
    return 'prosto' .  get_number_local($loccodi);
  }
}
