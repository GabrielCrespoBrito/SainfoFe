<?php

namespace App\Models\Produccion;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProduccionDetalle extends Model
{
  use UsesTenantConnection;

  protected $primaryKey = "mandetID";
  protected $table = "produccion_manual_det";
  public $fillable = [
      'mandetCodi',
      'mandetNomb',
      'mandetCant',
      'mandetCost',
      'mandetImpo',
      'manId',
      'USER_CREA',
      'USER_ECREA',

  ];
  const CREATED_AT = "USER_FCREA";
  const UPDATED_AT = "USER_FMODI";
}