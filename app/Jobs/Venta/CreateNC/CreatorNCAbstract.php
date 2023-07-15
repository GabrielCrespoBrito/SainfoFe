<?php

namespace App\Jobs\Venta\CreateNC;

use App\Venta;
use App\TipoDocumentoPago;
use App\Jobs\Venta\CreateNota\CreatorNotaAbstract;

abstract class CreatorNCAbstract extends CreatorNotaAbstract
{
  public function __construct(Venta $documento, array $data)
  {
    parent::__construct(  $documento , $data, TipoDocumentoPago::NOTA_CREDITO );
  }
}
