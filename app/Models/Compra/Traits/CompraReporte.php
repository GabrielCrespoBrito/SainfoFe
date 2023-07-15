<?php

namespace App\Models\Compra\Traits;

use App\Moneda;


trait CompraReporte
{
  public static function getDataExcell($mescodi, $tipo)
  {
    $data = [];

    $docs = self::with(
      ['cliente_with' => function ($q) {
        $q
          ->where('EmpCodi', empcodi())
          ->where('TipCodi', 'P');
      }, 'moneda']
    )
      ->where('MesCodi', $mescodi)
      ->where('EmpCodi', empcodi())
      ->get()
      ->toArray();

    $year = substr($mescodi, 0, 4);
    $mes = substr($mescodi, 4);

    for ($i = 0, $z = 1; $i < count($docs); $i++, $z++) {
      $doc = $docs[$i];
      $isDolar = $doc['moncodi'] == Moneda::DOLAR_ID;
      $tCambio = $isDolar ? $doc['CpaTCam'] : 0;

      $base = $isDolar ? $tCambio * $doc['Cpabase'] : $doc['Cpabase'];
      $igv = $isDolar ? $tCambio * $doc['CpaIGVV'] : $doc['CpaIGVV'];
      $total = $isDolar ? $tCambio * $doc['Cpatota'] : $doc['Cpatota'];
      $inafecta = 0;
      $totalDolar = $isDolar ? $doc['Cpatota'] : 0;

      if ($tipo == "sainfo") {
        $current_data = [
          $year, // Año
          '06', // Libro 
          $mes, // Mes
          $doc['TidCodi'],
          $doc['CpaSerie'],
          $doc['CpaNumee'],
          $doc['CpaFCpa'],

          // Cliente
          $doc['cliente_with']['PCRucc'],
          $doc['cliente_with']['PCNomb'],
          '', // Direccion
          $doc['cliente_with']['TDocCodi'], // Tipo de documento

          math()->addDecimal($base, 2),
          math()->addDecimal($igv, 2),
          math()->addDecimal($total, 2),
          $doc['moneda']['monnomb'],
          math()->addDecimal($totalDolar, 2),
          $tCambio,
        ];
      } else {
        $voucher = $mes . math()->addCero($z, 6);

        $current_data = [
          $voucher,
          $year,
          $mes,
          $doc['CpaFCpa'],
          $doc['TidCodi'],
          $doc['CpaSerie'],
          (int) $doc['CpaNumee'],

          // Cliente
          $doc['cliente_with']['PCRucc'],
          $doc['cliente_with']['PCNomb'],
          '', // FecREG
          $doc['moneda']['monnomb'],
          math()->addDecimal($totalDolar, 2),
          math()->addDecimal($base, 2),
          math()->addDecimal($igv, 2),
          math()->addDecimal($total, 2),
          $tCambio,
        ];
      }

      array_push($data, $current_data);
    }

    return $data;

  }

}