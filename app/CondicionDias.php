<?php

namespace App;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class CondicionDias extends Model
{
  use UsesTenantConnection;

  protected $primaryKey = "PgoCodi";
  protected $table = "condicion_pago_dias";
  protected $keyType = "string";
  public $timestamps = false;
  public $fillable = ['PgoDias', 'PgoCodi', 'ConCodi', 'empcodi'];
}
