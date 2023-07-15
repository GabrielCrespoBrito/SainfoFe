<?php

namespace App;

use App\Models\Traits\InteractWithMoneda;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class VentaCredito extends Model
{
    use 
    UsesTenantConnection,
    InteractWithMoneda;

    protected $table = "ventas_creditos";
    public $timestamps = false;
    public $fillable = ['item','VtaOper','monto','fecha_pago','forma_pago_id', 'MonCodi'];

  public function setMontoAttribute($value)
  {
    $this->attributes['monto'] = decimal( $value , 2 );
  }
}
