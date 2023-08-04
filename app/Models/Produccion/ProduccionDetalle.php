<?php

namespace App\Models\Produccion;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProduccionDetalle extends Model
{
  use UsesTenantConnection;

  protected $table = "produccion_manual_det";
}
