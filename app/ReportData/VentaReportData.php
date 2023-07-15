<?php

namespace App\ReportData;

use App\TipoDocumentoPago;
use Illuminate\Support\Facades\DB;

class VentaReportData 
{
  public function detraccion($mes)
  {
    list( $fecha_inicio, $fecha_final ) = mes_to_fecha_inicio_final($mes);

    return DB::connection('tenant')->table('ventas_cab')
    ->join('prov_clientes', function ($join) {
      $join
        ->on('prov_clientes.PCCodi', '=', 'ventas_cab.PCCodi')
        ->on('prov_clientes.EmpCodi', '=', 'ventas_cab.EmpCodi')
        ->where('prov_clientes.TipCodi', '=', 'C');
    })
      ->join('moneda', function ($join) {
        $join->on('moneda.moncodi', '=', 'ventas_cab.MonCodi');
      })    
      ->where('ventas_cab.VtaDetrCode', '!=',  '0')
      ->where('ventas_cab.EmpCodi', '=',  empcodi())
      ->whereBetween('ventas_cab.VtaFvta', [$fecha_inicio, $fecha_final])
      ->whereNotIn('ventas_cab.TidCodi', [ TipoDocumentoPago::NOTA_VENTA ,  50 ])
      ->select([
        'ventas_cab.EmpCodi',
        'ventas_cab.TidCodi',
        'ventas_cab.VtaFvta',
        'ventas_cab.VtaNumee',
        'ventas_cab.VtaSeri',
        'moneda.monabre',
        'ventas_cab.VtaOper',
        'prov_clientes.PCNomb',
        'prov_clientes.PCRucc',
        'ventas_cab.VtaExon',
        'ventas_cab.VtaImpo',
        'ventas_cab.VtaNumeR',
        'ventas_cab.MonCodi',
        'ventas_cab.VtaDetrPorc',
        'ventas_cab.VtaDetrTota',
        'ventas_cab.VtaTotalDetr',
        'ventas_cab.VtaDetrCode',     
      ])
      ->orderBy('TidCodi', 'asc')
      ->orderBy('VtaFvta', 'asc')
      ->get();
      // ->groupBy('TidCodi');


    return [];
  }

  public function xxx()
  {
    return [];
  }

  public function yyyy()
  {
    return [];
  }


}