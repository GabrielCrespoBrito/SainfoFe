<?php

namespace App\Jobs\EstadisticasMensual;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Moneda;
use App\TipoDocumentoPago;

class CalculatorEstadistica
{
  public $calculateDias = false;
  public $mescodi;
  public $lastSearch;

  public $stats_calculo = [];
  public $stats_estados = [];
  public $stats_dias = [];

  public $calculoCurrent;
  public $estadosCurrent;

  public function __construct( $calculoCurrent = [], $estadosCurrent = [], $calculateDias = true, $mescodi = null )
  {
    $this->calculoCurrent = $calculoCurrent;
    $this->estadosCurrent = $estadosCurrent;
    $this->calculateDias = $calculateDias;
    $this->mescodi = $mescodi;
    $this->setStatCalculos();
    $this->setStatDias();
    $this->setStatConfig();
  }

  public function setCurrentCalculos($calculoCurrent = [])
  {
  }

  public function setStatDias()
  {
    if(!$this->calculateDias){
      return;
    }

    $mesCantDias = (int) last(explode('-', mes_to_fecha_inicio_final($this->mescodi)[1]));

    for ($i = 1; $i <= $mesCantDias; $i++) {
      $this->stats_dias[$i] = [
        'cant' => 0,
        '01' => 0,
        '02' => 0,
      ];

    }
  }


  public function setStatCalculos()
  {
    $calculo = [
      'total' => 0,
      'base' => 0,
      'igv' => 0,
      'isc' => 0,
      'inafecta' => 0,
      'exonerada' => 0,
      'gratuita' => 0,
      'dcto' => 0,
      'icbper' => 0
    ];

    $this->stats_calculo['total'] = $calculo;
    $this->stats_calculo[TipoDocumentoPago::FACTURA] = $calculo;
    $this->stats_calculo[TipoDocumentoPago::BOLETA] = $calculo;
    $this->stats_calculo[TipoDocumentoPago::NOTA_CREDITO] = $calculo;
    $this->stats_calculo[TipoDocumentoPago::NOTA_DEBITO] = $calculo;
    $this->stats_calculo[TipoDocumentoPago::NOTA_VENTA] = $calculo;
  }

  public function setStatConfig()
  {
    $estados = [
      StatusCode::CODE_0001 => ['cantidad' => 0, 'total' => 0],
      StatusCode::CODE_0002 => ['cantidad' => 0, 'total' => 0],
      StatusCode::CODE_0003 => ['cantidad' => 0, 'total' => 0],
      StatusCode::CODE_0011 => ['cantidad' => 0, 'total' => 0],
      'total' => ['cantidad' => 0, 'total' => 0]
    ];

    $this->stats_estados['total'] = $estados;
    $this->stats_estados[TipoDocumentoPago::FACTURA] = $estados;
    $this->stats_estados[TipoDocumentoPago::BOLETA] = $estados;
    $this->stats_estados[TipoDocumentoPago::NOTA_CREDITO] = $estados;
    $this->stats_estados[TipoDocumentoPago::NOTA_DEBITO] = $estados;
    $this->stats_estados[TipoDocumentoPago::NOTA_VENTA] = $estados;
  }

  public function sumCalculoTo( $index, $calculo )
  {
    $this->stats_calculo[$index]['total'] += $calculo->total;
    $this->stats_calculo[$index]['base'] += $calculo->base;
    $this->stats_calculo[$index]['inafecta'] += $calculo->inafecta;
    $this->stats_calculo[$index]['exonerada'] += $calculo->exonerada;
    $this->stats_calculo[$index]['gratuita'] += $calculo->gratuita;
    $this->stats_calculo[$index]['igv'] += $calculo->igv;
    $this->stats_calculo[$index]['isc'] += $calculo->isc;
    $this->stats_calculo[$index]['dcto'] += $calculo->dcto;
    $this->stats_calculo[$index]['icbper'] += $calculo->icbper;
  }

  public function sumToDias($doc, $calculo)
  {
    ////
    # Sumar una a la cantida de documentos totales
    $dia = (int) last(explode('-', $doc->fecha));

    
    # Sumatoria al dia
    $isNC = $doc->tipodocumento == TipoDocumentoPago::NOTA_CREDITO;
    $soles = $doc->moneda == Moneda::SOL_ID ? $doc->importe : 0;
    $dolares = $doc->moneda == Moneda::DOLAR_ID ? $doc->importe : 0;
    $soles =  convertNegativeIfTrue($soles, $isNC);
    $dolares =  convertNegativeIfTrue($dolares, $isNC);
    
    # Sumar al total general
    $this->stats_dias[$dia]['cant'] += 1;
    $this->stats_dias[$dia]['01'] = $this->stats_dias[$dia]['01'] + $soles;
    $this->stats_dias[$dia]['02'] = $this->stats_dias[$dia]['02'] + $dolares;
    
    logger([ $dia , $doc->id ]);

    $this->lastSearch = $doc->fecha_modificacion > $this->lastSearch ? $doc->fecha_modificacion : $this->lastSearch;
  }

  public function sumEstadoTo($index, $estado, $calculo)
  {
    $this->stats_estados[$index][$estado]['cantidad'] += 1;
    $this->stats_estados[$index]['total']['cantidad'] += 1;

    $this->stats_estados[$index][$estado]['total'] += $calculo->total;
    $this->stats_estados[$index]['total']['total'] += $calculo->total;
  }

  public function generateCalculo($doc)
  {
    $total = $doc->importe;
    $base = $doc->base;
    $igv = $doc->igv;
    $isc = $doc->isc;
    $dcto = $doc->dcto;
    $tc = $doc->tc;
    $inafecta = $doc->inafecta;
    $exonerada = $doc->exonerada;
    $gratuita = $doc->gratuita;
    $icbper = $doc->icbper;
    $isNC = $doc->tipodocumento == TipoDocumentoPago::NOTA_CREDITO;

    return (object)  [
      'total' =>  $this->applyFormula($isNC, $tc, $doc->moneda, $total)  ,
      'base' =>  $this->applyFormula($isNC, $tc, $doc->moneda, $base)  ,
      'inafecta' => $this->applyFormula($isNC, $tc, $doc->moneda, $inafecta)  ,
      'exonerada' => $this->applyFormula($isNC, $tc, $doc->moneda, $exonerada)  ,
      'gratuita' => $this->applyFormula($isNC, $tc, $doc->moneda, $gratuita)  ,
      'igv' =>  $this->applyFormula($isNC, $tc, $doc->moneda, $igv)  ,
      'isc' =>  $this->applyFormula($isNC, $tc, $doc->moneda, $isc)  ,
      'dcto' =>  $this->applyFormula($isNC, $tc, $doc->moneda, $dcto)  ,
      'icbper' =>  $this->applyFormula($isNC, $tc, $doc->moneda, $icbper)  ,
    ];
  }

  /**
   * Si es nota de credito devolver negativo
   * 
   */
  public function applyFormula($isNc, $tc, $moneda, $monto)
  {
    if( $moneda == Moneda::SOL_ID ){
      return convertNegativeIfTrue($monto, $isNc);
    }

    return convertNegativeIfTrue($monto * $tc , $isNc);
  }

  public function setDoc($doc)
  {
    $calculo = $this->generateCalculo($doc);

    // Agregar a los calculos
    $this->sumCalculoTo('total', $calculo);
    $this->sumCalculoTo($doc->tipodocumento, $calculo);
    
    // Agregar a los estados 
    $this->sumEstadoTo('total', $doc->estado, $calculo);
    $this->sumEstadoTo($doc->tipodocumento, $doc->estado, $calculo);

    // Agregar a los dias
    if( $this->calculateDias ){
      $this->sumToDias($doc, $calculo);
    }

  }
}
