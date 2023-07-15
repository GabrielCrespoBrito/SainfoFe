<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\InteractWithMoneda;
use App\Repositories\BancoCuentaRepository;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class BancoEmpresa extends Model
{
	use 
	ModelEmpresaScope,
	InteractWithMoneda,
	UsesTenantConnection;
	
	protected $table = "bancos_cuenta_cte";	
	protected $primaryKey = "CueCodi";
	protected $keyType = "string";	
	const EMPRESA_CAMPO = "EmpCodi";
	public $timestamps = false;
	protected $guarded = [];

	public function banco()
	{
		return $this->belongsTo( Banco::class, 'BanCodi' , 'bancodi' );
	}

	public function moneda()
	{
		return $this->belongsTo( Moneda::class, 'MonCodi' , 'moncodi' );
	}

	public function cajas()
	{
		return $this->hasMany(Caja::class, 'CueCodi', 'CueCodi');
	}

	public function numOper()
	{
		$numOper = $this->banco->bancodi . $this->CueCodi . date('Ym');
		return $numOper;
	}

	public function fillCueCodi()
	{
		$cuentaNumero = $this->where('BanCodi' , $this->BanCodi )->count();
		$this->CueCodi = $this->BanCodi . agregar_ceros($cuentaNumero,2,1);
	}

	public static function createDefault($empcodi)
	{
		self::create([
			'BanCodi' => '01',
			'CueCodi' => '0101',
			'CueNume' => '000000000000000',
			'CueSald' => '0',
			'CueImSd' => '0',
			'CueImSc' => '0',
			'MonCodi' => '01',
			'test' => '1',
			'Detract' => '0',
			'EmpCodi' => $empcodi
		]);
	}

	/**
	 * Si la cuenta de banco esta aperturadad como banco
	 * 
	 * @return bool
	 */
	public function isAperturada() : bool
	{
    return $this->cajaAperturada() != null;
	}

  public function cajaAperturada() 
  {
    return $this->cajas->where('CajEsta', Caja::ESTADO_APERTURADA )->first();
  }
  


	/**
	 * Numero de la cuenta
	 *
	 * @return string
	 */
	public function numero()
	{
		return $this->CueNume;
	}
	

	public function repository()
	{
		return new BancoCuentaRepository( $this, empcodi() );
	}

  public function isSol()
  {
    return $this->MonCodi == Moneda::SOL_ID;
  }


  public function isDolar()
  {
    return ! $this->isSol();
  }


  public function getNombreFull()
  {
    return $this->banco->bannomb . ' ' . $this->CueNume;
  }

  

}