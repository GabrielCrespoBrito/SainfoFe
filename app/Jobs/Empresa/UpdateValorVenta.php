<?php

namespace App\Jobs\Empresa;

use App\Venta;
use App\Moneda;
use Illuminate\Support\Facades\DB;


class UpdateValorVenta 
{
  protected $igv_bace_uno;

  public function __construct()
  {
    $this->igv_bace_uno = config('app.parametros.igv_bace_uno');
  }

  public function getQuery($isVenta = true, $tableName)
  {
    $monedaCampo = $isVenta ? 'MonCodi' : 'moncodi';
    $tablaPadre = $isVenta ? 'ventas_cab' : 'compras_cab';
    $tablaPadreID = $isVenta ? 'VtaOper' : 'CpaOper';

    $campos = [
      "{$tableName}.Linea as id",
      "{$tableName}.DetPrec as precio",
      "{$tableName}.DetCant as cantidad",
      "{$tableName}.Detfact as factor",
      "{$tablaPadre}.{$monedaCampo} as moneda"
    ];

    if ($isVenta) {
      $campos[] = "{$tableName}.incluye_igv";
      $campos[] = "{$tableName}.DetBase as base_imponible";
      $campos[] = "{$tableName}.DetVSol as valor_sol";
      $campos[] = "{$tableName}.DetVDol as valor_dolar";
      $campos[] = "{$tablaPadre}.VtaTcam as tipo_cambio";


    } else {
      $campos[] = "{$tableName}.DetCSol as valor_sol";
      $campos[] = "{$tableName}.DetCDol as valor_dolar";
      $campos[] = "{$tablaPadre}.CpaTCam as tipo_cambio";
    }

    $query = DB::connection('tenant')->table($tableName)
      ->join($tablaPadre, "{$tablaPadre}.{$tablaPadreID}", '=', "{$tableName}.{$tablaPadreID}")
      ->select($campos)
      ->get()
      ->chunk(100);

    return $query;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $this->updates($this->getQuery(true, 'ventas_detalle'),  'ventas_detalle', true);
    $this->updates($this->getQuery(false, 'compras_detalle'), 'compras_detalle', false);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function updates($items_group, $tableName, $isVenta)
  {
    $campoValorSol = $isVenta ? 'DetVSol' : 'DetCSol';
    $campoValorDolar = $isVenta ? 'DetVDol' : 'DetCDol';

    foreach ($items_group as $items) {

      foreach ($items as $item) {
        $valor = $this->getValorVentaItem($item, $isVenta);        

        DB::connection('tenant')->table($tableName)
          ->where('Linea', '=',  $item->id)
          ->update([
            $campoValorSol => $valor->sol,
            $campoValorDolar => $valor->dolar,
          ]);
      }
    }
  }

  public function getValorVentaItem($item, $isVenta)
  {
    $isSol = $item->moneda ==  Moneda::SOL_ID;

    if( $isVenta ){
      $incluye_igv = $item->base_imponible == Venta::GRAVADA ? $item->incluye_igv : false;
    }
    else {
      $incluye_igv = ($isVenta ? $item->incluye_igv : false);
    }

    $incluye_igv = (bool) $incluye_igv;


    // Tipo de cambio
    $tipo_cambio =
    ($item->valor_dolar == 0 || $item->valor_sol == 0) ?
      $item->tipo_cambio : 
      decimal($item->valor_sol / $item->valor_dolar, 3);

    $importe = $item->precio * $item->cantidad;    
    $importe = $incluye_igv ? $importe / $this->igv_bace_uno : $importe;
    $valor_sol = $isSol ? $importe : $importe * $tipo_cambio;

    
    if( $importe == 0 || $tipo_cambio == 0 ){
      $valor_dolar = 0;
    }
    else {
      $valor_dolar = $isSol ? $importe / $tipo_cambio : $importe;
    }

    
    return (object) [
      'sol' => $valor_sol,
      'dolar' => $valor_dolar,
    ];

  }
}