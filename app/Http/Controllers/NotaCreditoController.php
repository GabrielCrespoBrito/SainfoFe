<?php

namespace App\Http\Controllers;

use App\Compra;
use App\Venta;
use App\Moneda;
use App\TipoDocumento;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;

class NotaCreditoController extends Controller
{

  public function getNotasCreditos( $isVenta = true , $term )
  {   
    $id_field = $isVenta ? 'VtaOper' : 'CpaOper';
    $search_field = $isVenta ? 'VtaNume' : 'CpaNume';
    $saldo_field = $isVenta ? 'VtaSald' : 'CpaSald';
    $fecha_field = $isVenta ? 'VtaFvta' : 'CpaFCpa';
    $importe_field = $isVenta ? 'VtaImpo' : 'CpaImpo';
    $moneda_field = $isVenta ? 'MonCodi' : 'moncodi';
    
    
    if( $isVenta ){
      $notas_creditos = Venta::with(['cliente_with'  => function ($q) use( $isVenta ) {
        $q->where('TipCodi',  $isVenta ?  'C' : 'P' );
      }])
      ->where('TidCodi', TipoDocumentoPago::NOTA_CREDITO)
      ->where($saldo_field, '>', 0);
      
      if ($term) {
        $notas_creditos->where($search_field, 'LIKE', '%' . $term . '%');
      }
    }
    else {
      $notas_creditos = Compra::with('cliente_with')
        ->where('TidCodi', TipoDocumentoPago::NOTA_CREDITO)
        ->where($saldo_field, '>', 0);

      if ($term) {
        $notas_creditos->where($search_field, 'LIKE', '%' . $term . '%');
      }      
    }

    $notas_creditos = $notas_creditos
      ->take(10)
      ->get();

    $data = [];

    foreach ($notas_creditos as $nota_credito) {

      $monto_str = $nota_credito->{$importe_field} . Moneda::getAbrev($nota_credito->{$moneda_field});
      $cliente_str = $nota_credito->cliente_with->PCRucc . ' ' . $nota_credito->cliente_with->PCNomb;

      $text = sprintf(
        '%s (%s) (%s) (%s)',
        $nota_credito->{$search_field},
        $monto_str,
        $cliente_str,
        $nota_credito->{$fecha_field}
      );

      $data[] = [
        'id' => $nota_credito->{$id_field},
        'moneda_id' => $nota_credito->{$moneda_field},
        'value' => $nota_credito->{$importe_field},
        'fecha' => $nota_credito->{$fecha_field},
        'numeracion' => $nota_credito->{$search_field},
        'text' => $text
      ];
    }

    return $data;

  }

    /**
     * Buscar .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchToPay(Request $request, $isVenta = 1)
    {
      $term = $request->input('data');
      $isVenta = (bool) $isVenta;
      $data = $this->getNotasCreditos($isVenta, $term);   
      return response()->json($data);
    }



}
