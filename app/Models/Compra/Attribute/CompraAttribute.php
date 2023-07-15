<?php

namespace App\Models\Compra\Attribute;

use App\TipoCambioMoneda;

trait CompraAttribute
{

  public function getCpaFCpaAttribute($value)
  {
    return is_null($value) ? date('Y-m-d') : $value;
  }

  public function getCpaFvenAttribute($value)
  {
    return is_null($value) ? date('Y-m-d') : $value;
  }

  public function getCpaFConAttribute($value)
  {
    return is_null($value) ? date('Y-m-d') : $value;
  }

  public function getVtaTcamAttribute($value)
  {
    return is_null($value) ? TipoCambioMoneda::ultimo_cambio() : $value;
  }

  public function getDetCantAttribute($value)
  {
    return is_null($value) ? "0.00" : $value;
  }

  public function getDetPesoAttribute($value)
  {
    return is_null($value) ? "0.00" : $value;
  }

  public function getFechaAttribute()
  {
    return $this->CpaFCpa;
  }

  public function getIdAttribute()
  {
    return $this->CpaOper;
  }

  public function getTipoCambioAttribute()
  {
    return $this->CpaTCam;
  }


}
