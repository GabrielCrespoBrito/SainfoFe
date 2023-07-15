<?php

namespace App\Http\Controllers\Reportes;

use App\GuiaSalida;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Venta;

class Reporte
{

  public static function getImporteByMonth()
  {
    $meses_recorridos = [];
    $meses_data = [];
    $total = 0;

    // for ($i = 1; $i < 31; $i++) {
    //   $cifra = (float) rand(500, 999999);
    //   $total += $cifra;
    //   array_push($meses_recorridos, $i);
    //   array_push($meses_data, $cifra);
    // }

    return [
      'data' => $meses_data,
      'labels' => $meses_recorridos,
      'total' => $total
    ];


    // @TODO Calculation diario de Importes
    // Loremp-Ipsum-Odlor
    // for ( $i = 1; $i <= $month_actual;  $i++) { 
    //   continue;
    //   $moncodi = $year_actual . ($i < 10 ? ("0" . $i ) : $i);
    //
    //   $ventas_mes = $empresa
    //   ->ventas
    //   ->where('MesCodi' , $moncodi);
    //   $total += $total_mes = decimal($ventas_mes->sum("VtaImpo"));
    //   array_push( $meses_data , $total_mes  );
    //   array_push( $meses_recorridos ,  $meses[$i] );
    // }    
  }

  public static function getDataDocumentsByMonth($month)
  {
    $ventas_group = Venta::where('MesCodi', $month)->get()->groupBy('TidCodi');

    $data = [
      '01' => [
        'total' => 0,
        'enviadas' => 0,
        'por_enviar' => 0,
        'no_aceptadas' => 0,
        'need_action' => 0,
      ],
      '03' => [
        'total' => 0,
        'enviadas' => 0,
        'por_enviar' => 0,
        'no_aceptadas' => 0,
        'need_action' => 0,
      ],
      '07' => [
        'total' => 0,
        'enviadas' => 0,
        'por_enviar' => 0,
        'no_aceptadas' => 0,
        'need_action' => 0,
      ],
      '08' => [
        'total' => 0,
        'enviadas' => 0,
        'por_enviar' => 0,
        'no_aceptadas' => 0,
        'need_action' => 0,
      ],
      '09' => [
        'total' => 0,
        'enviadas' => 0,
        'por_enviar' => 0,
        'no_aceptadas' => 0,
        'need_action' => 0,
      ],
    ];

    foreach ($ventas_group as $tidCodi => $grupo) {
      $total = $grupo->count();
      $cant_enviadas = $grupo->where('VtaFMail', StatusCode::CODE_EXITO_0001)->count();
      $cant_noaceptadas = $grupo->where('VtaFMail', StatusCode::CODE_EXITO_0002)->count();
      $cant_por_enviar = $grupo->where('VtaFMail', StatusCode::CODE_ERROR_0011)->count();
      $data[$tidCodi]['total'] = $total;
      $data[$tidCodi]['enviadas'] = $cant_enviadas;
      $data[$tidCodi]['por_enviar'] = $cant_por_enviar;
      $data[$tidCodi]['no_aceptadas'] = $cant_noaceptadas;
      $data[$tidCodi]['need_action'] = (int) ($cant_noaceptadas || $cant_por_enviar);
    }

    $guias = GuiaSalida::where('mescodi', '=', $month)
      ->where('GuiEFor', '=', "1")
      ->where('EntSal', '=', GuiaSalida::SALIDA)
      ->get();

    $total_guia = $guias->count();
    $cant_enviadas_guia  = $guias->where('fe_rpta', '=', "0")->count();
    $cant_por_enviar_guia = $total_guia - $cant_enviadas_guia;
    $cant_noaceptadas_guia = 0;

    $data['09']['total'] = $total_guia;
    $data['09']['enviadas'] = $cant_enviadas_guia;
    $data['09']['por_enviar'] = $cant_por_enviar_guia;
    $data['09']['no_aceptadas'] = $cant_noaceptadas_guia;
    $data['09']['need_action'] = (int) ($cant_noaceptadas_guia || $cant_por_enviar_guia);

    return $data;
  }
}