<?php

namespace App;

use App\Traits\DbSainfo;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
	use 
	DbSainfo,
	UsesTenantConnection;
	
	protected $table = 'transportista';
	protected $primaryKey = 'TraCodi';
	protected $fillable = ["Nombres",'Apellidos', 'TDocCodi', 'TraDire', 'TraRucc', 'TraTele', 'TraLice'];
	public $timestamps = false;
	const INITIAL_VALUE = "100";	

	protected static function boot()
  {
    parent::boot();
		static::creating(function($model){
			$lastCode = (int) self::max('TraCodi');
			$model->TraCodi = $lastCode == 0 ? self::INITIAL_VALUE : ($lastCode + 1);
			$model->EmpCodi = empcodi();

		});

    static::addGlobalScope('empresa', function ($query) {
      return $query->where( 'EmpCodi' , empcodi() );
    });

  //
    static::addGlobalScope('noEliminados', function ($query) {
      return $query->whereNot('UDelete', '=', '1');
    });
//

    
	}

	public function scopeDescripcion($query, $term)
	{
		$query->where('Nombres' , 'like', $term . '%');
	}

  public function getDocumentoNameComplete()
  {
    return sprintf('%s %s', TipoDocumento::getNombreLectura($this->getTipoDocumento()), $this->TraRucc );
  }

  public function getFullName()
  {
    return $this->Nombres . ' ' . $this->Apellidos;
  }

	public function descripcionComplete()
	{
		$nombre = $this->getFullName();
		$ruc = $this->TraRucc ? " ({$this->TraRucc})" : '';
		$licencia = $this->TraLice ? "({$this->TraLice})" : '';
		return "$nombre $ruc $licencia";  
	}
  
  public function getTipoDocumento()
  { 
    return $this->TDocCodi ??  TipoDocumento::NINGUNA;
  }

  /**
   * Tipo de documento de transportista
   */
  public function getNombres()
  {
    return $this->Nombres;
  }

  /**
   * Tipo de documento de transportista
   */
  public function getApellidos()
  {
    return $this->Apellidos;
  }


  public function getLicencia()
  {
    return $this->TraLice;
  }

  public function getDocumento()
  {
    return $this->TraRucc;
  }

  public function guias()
  {
    return $this->hasMany(GuiaSalida::class, 'tracodi', 'TraCodi' );
  }
}