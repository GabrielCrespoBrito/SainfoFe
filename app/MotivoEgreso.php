<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class MotivoEgreso extends Model
{
  use 
  ModelEmpresaScope, 
  UsesTenantConnection;

  protected $table       = 'egresos';  
  protected $primaryKey  = "EgrCodi";  
  protected $keyType  = "string";    
  public $timestamps = false;  
  public $fillable = ["EgrCodi", "Egrnomb", "EmpCodi"];
  const DEFAULT_INIT = "101";
  const EMPRESA_CAMPO = "EmpCodi";

  public static function UltimoId()
  {
    $last = self::max('EgrCodi');
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

  public static function getName($type) {
    return self::find($type)->Egrnomb;
  }

  public static function createDefault($empcodi)
  {
    $data = [
      "EgrCodi" =>  self::DEFAULT_INIT , 
      "Egrnomb" => 'EGRESO SIN DEFINIR' , 
      "EmpCodi" => $empcodi 
    ];

    self::create($data);
  }

  public function getIdAttribute()
  {
    return $this->EgrCodi;
  }

  public function getDescripcionAttribute()
  {
    return $this->Egrnomb;
  }  

}