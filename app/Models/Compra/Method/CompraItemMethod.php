<?php 

namespace App\Models\Compra\Method;

use App\Moneda;

trait CompraItemMethod
{
  public function isSol()
  {
    return $this->compra->moncodi == Moneda::SOL_ID;
  }
  
  public function getDetCodiAttribute()
  {
    return $this->attributes['Detcodi'];
  }

  public function getDetnombAttribute()
  {
    return $this->attributes['Detnomb'];
  }

  public function valorUnitario()
  {
    return $this->DetPrec;
  }

  public function precioUnitario()
  {
    return $this->DetPrec;
  }

  public function isDolar()
  {
    return !$this->isSol();
  }
}