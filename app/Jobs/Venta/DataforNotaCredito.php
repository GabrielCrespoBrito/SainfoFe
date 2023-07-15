<?php

namespace App\Jobs\Venta;

use App\Venta;
use App\TipoIgv;
use App\Repositories\TiposIGVRepository;
use App\SerieDocumento;
use App\TipoDocumentoPago;

class DataforNotaCredito
{
  public $documento;

  public function __construct(Venta $documento)
  {
    $this->documento  = $documento;
  }

  public function getSeries()
  {
    $letterSerie = substr($this->documento->VtaSeri, 0, 1);

    $series = SerieDocumento::where('empcodi', empcodi())
      ->where('tidcodi', TipoDocumentoPago::NOTA_CREDITO)
      ->where('usucodi', auth()->user()->usucodi )
      ->get();

    $data = [];

    foreach ($series as $serie) {
      if (substr($serie->sercodi, 0, 1) == $letterSerie) {
        $value = [
          'id' => $serie->ID,
          'text' => $serie->sercodi
        ];
        array_push($data, $value);
      }
    }
    return $data;
  }

  public function getItems()
  {
    $data = [];
    foreach( $this->documento->items as $item ){
      $value = [
        'id' => $item->Linea,
        'text' => $item->DetNomb,
        'importe' => $item->DetImpo,
        'cantidad' => $item->DetCant,
      ];
      array_push($data, $value);
    }
    return $data;
  }

  public function getTiposIGV()
  {
    $tipoigvrepository = new TiposIGVRepository(new TipoIgv());
    $tiposIgvs = $tipoigvrepository->all();
    $data = [];

    foreach( $tiposIgvs as $tipoIgv ){
      $value = [
        'value' => $tipoIgv->cod_sunat,
        'text' => $tipoIgv->descripcion,
      ];
      array_push($data, $value);
    }
    return $data;
  }

  public function handle()
  {
    return [
      'correlative' => $this->documento->numero(),
      'monto' => $this->documento->VtaImpo,
      'moneda' => $this->documento->MonCodi,
      'monedaAbbre' => $this->documento->getMonedaAbreviaturaSunat(),
      'tiposIgvs' => $this->getTiposIGV(),
      'series' => $this->getSeries(),
      'items' => $this->getItems(),
    ];
  }
}



