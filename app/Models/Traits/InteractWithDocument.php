<?php

namespace App\Models\Traits;

use App\TipoDocumentoPago;

trait InteractWithDocument
{
  public function isFactura()
  {
    return $this->TidCodi === TipoDocumentoPago::FACTURA;
  }

  public function isBoleta()
  {
    return $this->TidCodi === TipoDocumentoPago::BOLETA;
  }

  public function isNotaCredito()
  {
    return $this->TidCodi === TipoDocumentoPago::NOTA_CREDITO;
  }

  public function isNotaDebito()
  {
    return $this->TidCodi === TipoDocumentoPago::NOTA_DEBITO;
  }   
}