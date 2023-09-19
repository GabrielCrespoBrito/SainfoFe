<?php

namespace App\Jobs\Venta;

use App\TipoDocumentoPago;
use App\Venta;

class ConsultDocs
{
  public $fecha_desde;
  public $fecha_hasta;

    public function __construct($fecha_desde, $fecha_hasta)
    {
      $this->fecha_desde = $fecha_desde;
      $this->fecha_hasta = $fecha_hasta;
    }

    public function handle()
    {
      $models = Venta::where('VtaFvta', '>=' , $this->fecha_desde)
      ->where('VtaFvta', '<=', $this->fecha_hasta)
      ->whereIn('TidCodi', [
        TipoDocumentoPago::FACTURA,
        TipoDocumentoPago::BOLETA,
        TipoDocumentoPago::NOTA_CREDITO,
        TipoDocumentoPago::NOTA_DEBITO
      ])->get();
      
      foreach( $models as $model ){
        $rpta = $model->searchSunatGetStatus(true);
      }

      // _dd($this->fecha_desde, $this->fecha_hasta, $models);
      // exit();


    }
}
