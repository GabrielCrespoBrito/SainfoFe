<?php

namespace App;

use App\Util\ModelUtil\ModelUtil;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
	use 
  ModelUtil,
	UsesTenantConnection;
	
  protected $table       = 'moneda';
	protected $connection  = "mysql";
	protected $primaryKey  = "moncodi";  
	protected $keyType  = "string";
	public $descripcionKey = "monabre";  
	public $timestamps = false;
	public $fillable = ['moncodi', 'monnomb','monabre'];

  const SOL = "SOLES";
  const DOLARES = "DOLARES";
  CONST SOL_ID = "01";
  CONST DOLAR_ID = "02";

	public function esSol()
	{
  	return $this->moncodi === self::SOL_ID; 
  }

  public static function isSol($value)
  {
    return $value == self::SOL_ID;
  }

  public static  function isDolar($value)
  {
    return $value == self::DOLAR_ID;
  }  


	public function esDolar()
	{
		return $this->monnomb === self::DOLARES; 
  }

	public static function cambios_moneda( $moneda , $precio , $compra = true )
	{		
		// Precio del dolar en referencia a sol

		
		$precio_dolar = get_empresa()->opcion->tipo_cambio_publico;
		$isSol = Moneda::isSol($moneda);

		$cambio_valor = [ "dolar" => 0 , "sol" => 0];

		if($isSol){
			$cambio_valor['sol']   = $precio;
			$cambio_valor['dolar'] = ($precio / $precio_dolar);
		}

		else {
			$cambio_valor['sol'] = ($precio * $precio_dolar);
			$cambio_valor['dolar']   = $precio;
		}

		return $cambio_valor;
	}

	public static function getNombre($id)
	{
		return $id == self::SOL_ID ? 'SOLES' : 'DOLARES';
	}
	
	public static function getAbrev($id)
	{
		return $id == self::SOL_ID ? 'S/.' : 'US$.';
	}

  public static function getAbrevSunat($id)
  {
    return $id == self::SOL_ID ? 'PEN' : 'USD';
  }

	public static function createDefault($empcodi)
	{
		self::create([
			'moncodi' => '01',
			'monnomb' => 'SOLES',
			'monabre' => 'S./'
		]);

		self::create([
			'moncodi' => '02',
			'monnomb' => 'DOLARES',
			'monabre' => 'US$.'
		]);
	}


}