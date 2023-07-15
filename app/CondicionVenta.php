<?php

namespace App;

use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class CondicionVenta extends Model
{
	use UsesTenantConnection,
	ModelEmpresaScope;
	
	public $table = "condicion_cpra_vta";
	public $primaryKey = "CcvCodi";
	public $keyType = "string";	
	public $timestamps = false;
	protected $guarded = [];
	const ID_COTIZACION = "01";
	const ID_ORDER_COMPRA = "02";
	const ID_VENTA = "03";
	const ID_NOTA_VENTA = "04";
  const ID_ORDENCOMPRA = "05";
	const EMPRESA_CAMPO = "empcodi";
	const DEFAULTS = 
  [
		'01' =>'-TODO SERVICIO ES MAS EL  I.G.V.\r- 50 % DE ADELANTO PARA EMPEZAR EL TRABAJO \r\n-LOS PRECIOS ESTAN SUJETO A MODIFICACIONES SIN PREVIO AVISO.',

		'02' => '*PRECIOS INCLUYEN EL I.G.V.\r\n*CHEQUE DIFERIDO A 90 DIAS\r\n*FIERROSOL RECOJE EL MATERIAL EN SUS INSTALACIONES',

		'03' => '- Es responsabilidad del cliente revisar los datos del documento una vez emitido.\n - No se aceptan cambios ni devoluciones de materiales.\n - La mercaderia viaja por cuenta y riesgo del comprador.\n- Los materiales no recogidos pasado el 5to día pagaran el 1% diario por almacenaje.',

    '05' => '- LETRA 90 DIAS',
	];

	public static function getCondicion($id)
  {
		$cond = self::find( $id );
		return is_null($cond) ? "" : $cond->CcvDesc;
	}
	public static function createDefault( $empcodi )
	{
		$condiciones = self::DEFAULTS;
		foreach( $condiciones as $code =>  $condicion ){
			self::create([
				'CcvCodi' => $code,
				'CcvDesc' => $condicion,
				'empcodi' => $empcodi,
			]);
		}
	}
	public static function find( $id, $empcodi = null )
  {
		$empcodi = $empcodi ?? empcodi();
		return self::where('CcvCodi', $id )->where('empcodi', $empcodi)->first();
	}

	public static function getDefaultCotizacion()
  {
		return self::getCondicion(self::ID_COTIZACION);
	}

  public static function getByCotizacion($tipo)
  {
    return
      $tipo == Cotizacion::ORDEN_COMPRA ?
      self::getCondicion(self::ID_ORDENCOMPRA) :
      self::getCondicion(self::ID_COTIZACION);
  }

	public static function getDefaultNotaVenta()
  {
		return self::getCondicion(self::ID_NOTA_VENTA);
	}

	public static function saveDefaultCotizacion($descripcion)
  {
		return self::find(self::ID_COTIZACION)->update(['CcvDesc' => $descripcion]);
	}

  public static function saveDefaultOrdenCompra($descripcion)
  {
    return self::find(self::ID_ORDENCOMPRA)->update(['CcvDesc' => $descripcion]);
  }

	public static function getDefault()
  {
		return self::getCondicion(self::ID_VENTA);
	}
	public static function saveDefault($descripcion)
  {
		return self::find(self::ID_VENTA)->update(['CcvDesc' => $descripcion]);
	}
}