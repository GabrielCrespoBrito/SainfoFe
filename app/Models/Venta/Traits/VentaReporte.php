<?php

namespace App\Models\Venta\Traits;

use App\Moneda;
use App\TipoDocumento;

trait VentaReporte
{
  public static function getDataExcell($mescodi , $tipo)
  {
    $data = [];

    $docs = self::with(
      ['cliente_with' => function ($q) {
        $q
          ->where('EmpCodi', empcodi())
          ->where('TipCodi', 'C');
      }, 'moneda']
    )
    ->where('MesCodi', $mescodi)
    ->whereIn('TidCodi', ["01","03","07","08"] )
    ->where('EmpCodi', empcodi())
    ->get()
    ->toArray();


    $year = substr($mescodi,0,4);
    $mes = substr($mescodi,4);

    for( $i = 0, $z = 1; $i < count($docs); $i++, $z++ )
    {
      $doc = $docs[$i];
      $isDolar = $doc['MonCodi'] == Moneda::DOLAR_ID;
      $tCambio = $isDolar ? $doc['VtaTcam'] : 0;

      $base = $isDolar ? $tCambio * $doc['Vtabase'] : $doc['Vtabase'];
      $igv = $isDolar ? $tCambio * $doc['VtaIGVV'] : $doc['VtaIGVV'];
      $total = $isDolar ? $tCambio * $doc['VtaTota'] : $doc['VtaTota'];
      $inafecta = $isDolar ? $tCambio * $doc['VtaInaf'] : $doc['VtaInaf'];
      $totalDolar = $isDolar ? $doc['VtaTota'] : 0;

      if( $tipo == "sainfo" ){
        $current_data = [
          $year , // Año
          '05', // Libro 
          $mes, // Mes
          $doc['TidCodi'],
          $doc['VtaSeri'],
          $doc['VtaNumee'],
          $doc['VtaFvta'],
          
          // Cliente
          $doc['cliente_with']['PCRucc'],
          $doc['cliente_with']['PCNomb'],
          '', // Direccion
          $doc['cliente_with']['TDocCodi'], // Tipo de documento

          math()->addDecimal($base,2),
          math()->addDecimal($igv,2),
          math()->addDecimal($total,2),
          $doc['moneda']['monnomb'],
          math()->addDecimal($totalDolar,2),
          $tCambio,
          // Estado
          $doc['VtaEsta'], 
          // Doc ref
          $doc['VtaTDR'], 
          $doc['VtaSeriR'],
          $doc['VtaNumeR'],
          $doc['VtaFVtaR'],          
        ];
      }

      else {
        $voucher = $mes . math()->addCero($z , 6) ;

        $current_data = [
          $voucher,
          $year,
          $mes,
          $doc['VtaFvta'],
          $doc['TidCodi'],
          $doc['VtaSeri'],
          (int) $doc['VtaNumee'],

          // Cliente
          $doc['cliente_with']['PCRucc'],
          $doc['cliente_with']['PCNomb'],
          '', // FecREG
          $doc['moneda']['monnomb'],
          $doc['VtaEsta'],
          0, // Exporta
          math()->addDecimal($totalDolar,2),
          math()->addDecimal($base,2),
          math()->addDecimal($igv,2),
          math()->addDecimal($total,2),
          math()->addDecimal($inafecta,2),
          $tCambio,

          // Doc ref
          $doc['VtaTDR'],
          $doc['VtaSeriR'],
          $doc['VtaNumeR'],
          $doc['VtaFVtaR'], 
        ];
      }

      array_push( $data , $current_data );
    }
  
    return $data;

    // $data = Venta::getDataExcell($request->periodo, $request->para);

  }
}
