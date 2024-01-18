<?php

namespace App;

use App\Venta;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Zona extends Model
{
  use UsesTenantConnection;
  
  protected $table = "zona";
  protected $primaryKey = "ZonCodi";
  protected $keyType = "string";  
  public $timestamps = false;   
  public $fillable = [
    'ZonCodi', 'ZonNomb'
  ];
  public $incrementing = false; 
  const DEFAULT_ZONA = "0100";


  public function isDefault()
  {
    return $this->ZonCodi == self::DEFAULT_ZONA;
  }

  public static function createDefault()
  {
     self::create([
      'ZonCodi' => self::DEFAULT_ZONA,
      'ZonNomb' => ".ZONA SIN DEFINIR",
     ]);
  }

  public function ventas() {
    return $this->hasMany( Venta::class, 'ZonCodi', 'ZonCodi' );
  }

  public function cotizaciones() {
    return $this->hasMany( Venta::class, 'zoncodi', 'ZonCodi' );
  }

  public function compras() {
    return $this->hasMany( Venta::class, 'zoncodi', 'ZonCodi' );
  }

  public function guias() {
    return $this->hasMany( Venta::class, 'zoncodi', 'ZonCodi' );
  }

}