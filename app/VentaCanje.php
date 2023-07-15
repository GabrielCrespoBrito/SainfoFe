<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class VentaCanje extends Model
{
  use UsesTenantConnection;
  protected $table = "ventas_canje";
}
