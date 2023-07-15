<?php

namespace App\Empresa\Traits;

use App\Empresa;

trait InteractWithPrinter
{
  /**
   * Si tiene la impresiòn directa configurada
   * 
   * @return bool
   */
  public function imprimirDirect()
  {
    return (bool) $this->{Empresa::CAMPO_IMPRESION_DIRECTA};
  }

  /**
   * Nombre de la impresora
   * 
   * @return string|null
   */
  public function printName()
  {
    return  $this->{Empresa::CAMPO_NOMBRE_IMPRESORA};
  }

  /**
   * Nombre de la impresora
   * 
   * @return string|null
   */
  public function printQty()
  {
    return (int) $this->{Empresa::CAMPO_NUMERO_COPIAS};
  }

}
