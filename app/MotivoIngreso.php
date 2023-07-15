<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class MotivoIngreso extends Model
{

  use
  ModelEmpresaScope, 
  UsesTenantConnection;

  protected $table       = 'ingresos';  
  protected $primaryKey  = "IngCodi";  
  protected $keyType  = "string";    
  public $timestamps = false;  
  public $fillable = ["IngCodi", "IngNomb", "EmpCodi"];
  const DEFAULT_INIT = "101";
  const EMPRESA_CAMPO = "EmpCodi";
  const SIN_DEFINIR = 999;

  public static function UltimoId()
  {
    $last = self::max('IngCodi');
    return is_null( $last ) ? self::DEFAULT_INIT : agregar_ceros($last);
  }

  public function movimientos()
  {
    return $this->hasMany( CajaDetalle::class, 'EgrIng' );
  }

  public function codigo(){
    return $this->{$this->fillable[0]};
  }

  public function nombre(){
    return $this->{$this->fillable[1]};
  }

  public static function createDefault($empcodi)
  {
    $data = [
      "IngCodi" =>  self::DEFAULT_INIT , 
      "IngNomb" => 'INGRESO SIN DEFINIR' , 
      "EmpCodi" => $empcodi 
    ];

    self::create($data);
  }

  public function getIdAttribute()
  {
    return $this->IngCodi;
  }

  public function getDescripcionAttribute()
  {
    return $this->IngNomb;
  }

}
