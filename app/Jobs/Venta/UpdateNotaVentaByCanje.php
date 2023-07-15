<?php

namespace App\Jobs\Venta;

use App\Venta;

class UpdateNotaVentaByCanje
{
  public $venta;
  public $canjeQuery;

  public function __construct(Venta $venta, $canjeQuery)
  {
    $this->venta = $venta;
    $this->canjeQuery = $canjeQuery;
  }

  public function handle()
  {
    $this->venta->update([
      'TipoOper' => Venta::TIPO_CANJEADOR,
      'VtaSald' => 0,
      'VtaPago' => $this->venta->VtaImpo,
    ]);

    $vtaoper = $this->venta->VtaOper;
    
    $docs = $this->canjeQuery->get();

    foreach( $docs as $doc ){

      $doc = Venta::find($doc->VtaOper);

      $doc->update([
        'VtaOperC' => $vtaoper,
        'VtaPago' => $doc->VtaImpo,
        'TipoOper' => Venta::TIPO_CANJEADA,
        'VtaSald' => 0,
      ]);
    }

  }
}
