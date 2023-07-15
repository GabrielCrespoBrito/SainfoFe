<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Familia extends Model
{
  use UsesTenantConnection;

  protected $table       = 'familias';  
  protected $keyType = 'string';
  public $primaryKey = ['famCodi',"empcodi","gruCodi"];
  public $timestamps   = false;
  public $incrementing = false;
  public $fillable = ["gruCodi", "famNomb","famCodi", 'empcodi'];
  
  public function grupo()
  {
    return $this->belongsTo( Grupo::class, 'gruCodi', 'GruCodi');
  }

  public static function last_id($grucodi, $empcodi = null ){
    $empcodi = $empcodi ?? empcodi();

    $max = self::where( 'empcodi' , $empcodi )
    ->where('gruCodi' , $grucodi)
    ->max('famCodi');

    return is_null($max) ? "01" : (string) agregar_ceros($max,2);
  }

  public static function find($famcodi){
    return self::where('famCodi',$famcodi)->where('empcodi', empcodi())->first();
  }
  public function setFamNombAttribute($value)
  {
    $this->attributes['famNomb'] = strtoupper($value);
  }
  protected function setKeysForSaveQuery(Builder $query)
  {
    $keys = $this->getKeyName();
    if(!is_array($keys)){
      return parent::setKeysForSaveQuery($query);
    }
    foreach($keys as $keyName){
      $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
    }
    return $query;
  }

  protected function getKeyForSaveQuery($keyName = null)
  {
    if(is_null($keyName)){
      $keyName = $this->getKeyName();
    }
    if (isset($this->original[$keyName])) {
      return $this->original[$keyName];
    }
    return $this->getAttribute($keyName);
  }  

  public static function createDefault($empcodi, $grucodi = null, $name = null, $famcodi = null)
  {
    $empcodi = $empcodi ?? empcodi();
    $grucodi = $grucodi ?? '00';
    $name = $name ?? 'SIN DEFINIR';
    $famcodi = $famcodi ?? '00';
    
    return self::create([      
      "gruCodi" => $grucodi,
      "famNomb" => $name,
      "famCodi" => $famcodi,
      "empcodi" => $empcodi,
    ]);
  }

  public function getIdAttribute()
  {
    return $this->famCodi;
  }

  public function getDescripcionAttribute()
  {
    return $this->famNomb;
  }


}