<?php

namespace App\Models\UserLocal;

use App\Empresa;
use App\Local;
use App\SerieDocumento;
use App\User;
use App\Util\ModelUtil\ModelEmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserLocal extends Model
{
	use ModelEmpresaScope;

	protected $table = "usuario_local";
	protected $connection = "mysql";
	protected $primaryKey = 'usucodi';
  public $incrementing = false;
  protected $keyType = "string";  	
	public $timestamps = false;
	protected $guarded = [];
	const DEFAULT = 1;
	const NOT_DEFAULT = 0;
	const EMPRESA_CAMPO = 'empcodi';

  public static function find( $usucodi , $loccodi )
  {	
  	return self::where('usucodi', $usucodi)->where('loccodi', $loccodi )->firstOrfail();
  }

	public function user()
	{
		return $this->belongsTo( User::class, 'usucodi', 'usucodi' );
	}

	public function empresa()
	{
		return $this->belongsTo( Empresa::class, 'empcodi', 'empcodi' );
	}

	public function local()
	{
		return $this->belongsTo( Local::class, 'loccodi', 'LocCodi' );
	}
    
	public function isDefault()
	{
		return $this->defecto == self::DEFAULT;
	}

  public function getSeries()
  {
    return SerieDocumento::where('empcodi', $this->empcodi )
      ->where('loccodi', $this->loccodi)
      ->where('usucodi', $this->usucodi )
      ->get();
  }

	public function allDefault()
	{
		UserLocal::where('usucodi', $this->usucodi)->update(['defecto' => self::DEFAULT]);
	}

	/**
	 * Quitar asignación como local por defecto a un usuario
	 * 
	 */
	public function cleanDefault($usucodi , $empcodi = null )
	{
		$empcodi = $empcodi ?? empcodi();

		DB::table('usuario_local')
			->where('empcodi', $empcodi )
			->where('usucodi', $usucodi)
			->update(['defecto' => '0' ]);

		return $this;
	}


	/**
	 * Establecer local por defecto a un usuario
	 * 
	 */
	public function setDefault($usucodi, $loccodi, $empcodi = null )
	{
    $empcodi = $empcodi ?? empcodi();

		DB::table('usuario_local')
			->where('empcodi', $empcodi)
			->where('usucodi', $usucodi)
			->where('loccodi', $loccodi)
			->update(['defecto' => '1']);

		return $this;
	}


  public function setDefecto()
  {
    $this->setDefault( $this->usucodi , $this->loccodi, $this->empcodi );
  }

  public function deleteShort()
  {
    DB::table('usuario_local')
    ->where('loccodi', $this->loccodi )
      ->where('empcodi', $this->empcodi )
      ->where('usucodi', $this->usucodi )
      ->delete();
  }


  /**
   * Establecer local por defecto a un usuario
   * 
   */
  public static function create_($usucodi, $loccodi, $empcodi,  $defecto = 1, $sercodi = '0000', $numcodi = '000000' )
  {
    return UserLocal::create([
      'usucodi' => $usucodi,
      'loccodi' => $loccodi,
      'empcodi' => $empcodi,
      'sercodi' => $sercodi,
      'numcodi' => $numcodi,
      'defecto' => $defecto,
    ]);
  }
  
}