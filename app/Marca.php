<?php

namespace App;

use App\Traits\ModelTrait;
use App\Util\ModelUtil\ModelEmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Marca extends Model
{
  use 
  ModelEmpresaScope,
  ModelTrait,
  UsesTenantConnection;
  
  protected $table       = 'marca';  
  public $timestamps   = false;
  public $incrementing = false;
  protected $keyType   = "string";
  protected $primaryKey = "MarCodi";
  public $fillable = [ "MarCodi", 'MarNomb' , 'empcodi'];
  const EMPRESA_CAMPO = "empcodi";

  public function productos()
  {
    return $this->hasMany(Producto::class, 'marcodi', 'MarCodi');
  }

  public function scopeNoDeleted($query)
  {
    return $query->where('UDelete', '!=', '*');
  }

  public function deleteSoft()
  {
    if( $this->productos->count() ){
      $this->deleteDb();
      return true;
    }

    return $this->delete();
  }




  public static function last_id($agregate = 1)
  {
    if( $codigo = self::max('MarCodi') ){
      return agregar_ceros($codigo,2,$agregate);
    }
    return "01";
  }

  public static function find($marcodi, $empcodi = null)
  {
    $empcodi = $empcodi ?? empcodi();
    return self::where('MarCodi',$marcodi)->where('empcodi', $empcodi  )->first();
  }


  public static function createDefault($empcodi)
  {
    return self::create([
      "MarCodi" => '00',
      "MarNomb" => 'SIN DEFINIR',
      "empcodi" => $empcodi,
    ]);
  }


  public function setMarNombAttribute($value)
  {
    $this->attributes['MarNomb'] = strtoupper($value);
  }

  public function getIdAttribute()
  {
    return $this->MarCodi;
  }

  public function getDescripcionAttribute()
  {
    return $this->MarNomb;
  }

}
