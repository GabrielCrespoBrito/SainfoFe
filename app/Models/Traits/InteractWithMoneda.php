<?php

namespace App\Models\Traits;

use App\Moneda;

trait InteractWithMoneda
{
  public function isDolar()
  {
    return $this->MonCodi == Moneda::DOLAR_ID;
  }

  public function isSol()
  {
    return $this->MonCodi == Moneda::SOL_ID;
  }

  public function getMonedaNombre()
  {
    return Moneda::getNombre($this->MonCodi);
  }

  public function getMonedaAbreviatura()
  {
    return Moneda::getAbrev($this->MonCodi);

  }

  public function getMonedaAbreviaturaSunat()
  {
    return Moneda::getAbrevSunat($this->MonCodi);
  }

}