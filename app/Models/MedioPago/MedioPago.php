<?php

namespace App\Models\MedioPago;

use App\TipoPago;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class MedioPago extends Model
{
  use UsesTenantConnection;

  protected $table = "medios_pagos";

  const NO_DEFAULT = 0;
  const DEFAULT = 1;
  
  const ESTADO_NO_USO = 0;
  const ESTADO_USO = 1;


  const SIN_DEFINIR = "00";
  const CODIGO_SINDEFINIR = "00";
  const CODIGO_CREDITO = "08";

  public $guarded = [];

  public function tipo_pago_parent()
  {
    return $this->belongsTo( TipoPago::class, 'tipo_pago', 'TpgCodi' );
  }

  public function isUso()
  {
    return $this->uso == self::ESTADO_USO;
  }

  public function isNoUso()
  {
    return !$this->isUso();
  }

  public function toggleEstado()
  {
    $this->updateEstado( $this->isUso() ? self::ESTADO_NO_USO : self::ESTADO_USO );
  }

  public function updateEstado($estado)
  {
   $this->update([
    'uso' => $estado
   ]);
  }

  public function getTpgCodiAttribute()
  {
    return $this->tipo_pago_parent->TpgCodi;
  }

  public function getTpgNombAttribute()
  {
    return $this->tipo_pago_parent->TpgNomb;
  }

  public function getTdoBancAttribute()
  {
    return $this->tipo_pago_parent->TdoBanc;
  }

  public function isBancario()
  {
    return $this->tipo_pago_parent->TdoBanc == TipoPago::IS_BANCARIO;
  }

  public function isDefault()
  {
    return $this->default == self::DEFAULT;
  }  
}
