<?php

namespace App;

use App\Traits\DbSainfo;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
  use
  DbSainfo,
  UsesTenantConnection;

	protected $table = "vehiculo";
	protected $primaryKey = "VehCodi";
	protected $keyType = "string";	
  public $timestamps = false;
  public $fillable = ['VehPlac', 'VehMarc','VehInsc'];

  const EMPRESA_CAMPO = "empcodi";
  const INITIAL_VALUE = 1000;

	protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('empresa', function ($query) {
      return $query->where( 'empcodi' , empcodi() );
    });

    static::creating(function($model){
			$lastCode = (int) self::max('VehCodi');
			$model->VehCodi = $lastCode == 0 ? self::INITIAL_VALUE : ($lastCode + 1);
			$model->empcodi = empcodi();
		});

	}

	public function scopeDescripcion($query, $term)
	{
		$query->where('VehPlac' , 'like', $term . '%' );
	}

	public function descripcionComplete()
	{
		$placa = $this->VehPlac;
		$marca = $this->VehMarc ? " ({$this->VehMarc})" : '';
		return "$placa $marca";  
	}

  public function empresa()
  {
    return $this->belongsTo( Empresa::class, 'EmpCodi' , 'empcodi' );
  }

  public static function allByEmpresa()
  {
    return self::where('empcodi', get_empresa('id'))->get();
  }

  public function guias()
  {
    return $this->hasMany(GuiaSalida::class, 'VehCodi', 'VehCodi');
  }

  public function getPlaca()
  {
    return $this->VehPlac;
  }

  public function getConstanciaInscripcion()
  {
    return $this->VehInsc;
  }
}
