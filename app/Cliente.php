<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Cliente extends Model
{
	protected $table = "prov_clientes";
	protected $primaryKey = ["PCCodi","EmpCodi"];
	protected $keyType = "string";	
	const CREATED_AT = "User_FCrea";
	const UPDATED_AT = "User_FModi";

	public static function find($id){
		return self::where('PCCodi',$id)->where('EmpCodi', get_empresa('id'))->first();
	}

	public function tipo_documento()
	{
		return $this->belongsTo( TipoDocumento::class, 'TDocCodi' , 'TDocCodi' );
	}

	public function ubigeo(){
		return $this->belongsTo( Ubigeo::class, 'PCDist' , 'ubicodi' );
	}

	public function compras()
	{
		return $this->hasMany( Venta::class, 'PCCodi' , 'PCCodi' );
	}

	public function getTDocCodiAttribute($value){
		return (string) $value;
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



}
