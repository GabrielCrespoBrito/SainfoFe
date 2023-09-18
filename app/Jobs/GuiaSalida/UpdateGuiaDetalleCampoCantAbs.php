<?php

namespace App\Jobs\GuiaSalida;

use App\Empresa;
use App\GuiaSalida;
use App\GuiaSalidaItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateGuiaDetalleCampoCantAbs
{
  public function handle($code = null)
  {
    if ($code != null) {
      $empresa = Empresa::find($code);
      empresa_bd_tenant($empresa->id());
      $this->processEmpresaGuias();
      return;
    }

    $empresas_group = Empresa::all()->chunk(50);
    foreach ($empresas_group as $empresas) {
      foreach ($empresas as $empresa) {
        try {
          empresa_bd_tenant($empresa->id());
          $this->processEmpresaGuias($empresa);
          sprintf("@SUCCESS (UpdateGuiaDetalleCampoCantAbs) EMPRESA (%s)", $empresa->id());
        } catch (\Throwable $th) {
          sprintf("@ERROR (UpdateGuiaDetalleCampoCantAbs) Empresa (%s) Error (%s)", $empresa->id() , $th->getMessage() );
        }
      }
    }
  }

  public function processEmpresaGuias()
  {
    DB::connection('tenant')
    ->table('guia_detalle')
    ->join('guias_cab', 'guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
    ->orderBy('guias_cab.GuiOper')
    ->select([
      'guias_cab.EntSal as tipo',
      'guia_detalle.Linea as id',
      'guia_detalle.DetNomb as nombre',
      'guia_detalle.GuiOper as guia_id',
      'guia_detalle.Detcant as cantidad',
      'guia_detalle.CpaVtaCant as cantidad_absoluta',
      'guia_detalle.DetFact as factor'
      ])
    ->chunk(100, function($detalles) {
      foreach( $detalles as $detalle)  {
        if( $detalle->cantidad == 0 && $detalle->cantidad_absoluta == 0 ){
          continue;
        }
        
        $factor = $detalle->factor == 0 ? 1 : $detalle->factor;
        $isIngreso = $detalle->tipo == GuiaSalida::INGRESO;
        $cantidad_absoluta = $factor * $detalle->cantidad;
        $cantidad_absoluta = convertNegativeIfTrue($cantidad_absoluta, $isIngreso == false);


        DB::connection('tenant')->table('guia_detalle')

        ->where('Linea', '=', $detalle->id )
        ->update([
            'CpaVtaCant' => $cantidad_absoluta,
            'DetTipo' => $isIngreso ? GuiaSalidaItem::TIPO_COMPRA : GuiaSalidaItem::TIPO_VENTA
        ]);
      }
    });
  }
}