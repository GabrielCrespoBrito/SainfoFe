<?php

namespace App;

use App\Traits\DbSainfo;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class EmpresaTransporte extends Model
{
	use
	DbSainfo,
  UsesTenantConnection;

	protected $table = "empresa_transporte";
	protected $primaryKey = "EmpCodi";
	protected $fillable = ["EmpNomb",'EmpRucc', 'mtc'];
	public $timestamps = false;
	const INITIAL_VALUE = "100";
	
	protected static function boot()
  {
    parent::boot();
    static::addGlobalScope('empresa', function ($query) {
      return $query->where( 'empresa_id' , empcodi() );
		});

		static::creating(function($model){
			$lastCode = (int) self::max('EmpCodi');
			$lastCode = $lastCode == 0 ? self::INITIAL_VALUE : ($lastCode+1);
			$model->EmpCodi = $lastCode;
			$model->empresa_id = empcodi();
		});
	}

	public function scopeDescripcion($query, $term)
	{
		$query->where('EmpNomb' , 'like', $term . '%');
	}

	public function descripcionComplete()
	{
		$nombre = $this->EmpNomb;
		$ruc = $this->EmpRucc ? " ({$this->EmpRucc})" : '';
		return "$nombre $ruc";  
	}

  public function guias()
  {
    return $this->hasMany(GuiaSalida::class, 'TraOper', 'EmpCodi');
  }

  public function getDocumento()
  {
    return $this->EmpRucc;
  }

  public function getNombre()
  {
    return $this->EmpNomb;
  }

  public function getRegistroMTC()
  {
    return  $this->mtc;
  }
}