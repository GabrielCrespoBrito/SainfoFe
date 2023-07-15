<?php

namespace App;

use App\Traits\ModelTrait;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class Detraccion extends Model
{
  use UsesSystemConnection;
  
  protected $table = "detracciones";
  public $timestamps = false;

  use ModelTrait;

  public function getDescripcionFullAttribute()
  {
    return $this->cod_sunat . ' - ' .  $this->descripcion . ' (' . $this->porcentaje  .'%)'; 
  }

  public static function getPorcentajeDetraccion($code = null)
  {
    return optional(self::where('cod_sunat',$code)->first())->porcentaje;
  }
  


}