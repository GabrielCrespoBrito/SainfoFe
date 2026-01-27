<?php

namespace App;

use App\Repositories\TipoMovimientoRepository;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class TipoMovimiento extends Model
{
	use UsesSystemConnection;

  protected $table       = 'tipomov';  
  protected $primaryKey   = "Tmocodi";  
  protected $keyType   = "string";
  protected $timetamps   = false;
  const DEFAULT_TIPO = "VENTAS";
  const DEFAULT_SALIDA = "S001";  
  const DEFAULT_INGRESO = "I002";
  const NC_VENTA = "I003";
  const NC_COMPRA = "S002";
  const INGRESO_INVENTARIO = "I001";
  const INVENTARIO_SALIDA = "S005";
  const INVENTARIO_ORDENCOMPRA = "S006";

  const INGRESO_PRODUCCION = "I007";
  const SALIDA_PRODUCCION = "S007";

  // 'I001', 'INVENTARIO INGRESO', 'N', 'I', 'S', 'S', 'N', 'S', NULL
  // 'I002', 'COMPRAS', 'S', 'I', 'S', 'S', 'N', 'S', '02'
  // 'I003', 'DEVOLUCION DE VENTA', 'S', 'I', 'S', 'N', 'S', 'S', NULL
  // 'I004', 'GUIA INGRESO', 'N', 'I', 'S', 'N', 'N', 'S', NULL
  // 'I005', 'TRAS.GUIA INGRESO', NULL, 'I', NULL, NULL, NULL, 'S', NULL
  // 'S001', 'VENTAS', 'S', 'S', 'S', 'N', 'S', 'S', '01'
  // 'S002', 'DEVOLUCION DE COMPRA', 'N', 'S', 'S', 'N', 'S', 'S', NULL
  // 'S003', 'GUIA SALIDA', 'N', 'S', 'S', 'N', 'N', 'S', NULL
  // 'S004', 'TRAS.GUIA SALIDA', NULL, 'S', NULL, NULL, NULL, 'S', NULL
  // 'S005', 'INVENTARIO SALIDA', 'N', 'S', 'S', 'S', NULL, 'S', NULL

	public static function activos()
	{		
		return self::where('TmoInSa','S')->get();
	}

	public static function salidaVenta()
	{		
		return self::where('Tmocodi', self::DEFAULT_SALIDA )->get();
	}

	public static function getTipoNC( bool $is_venta)
	{		
		$code = $is_venta ? self::NC_VENTA : self::NC_COMPRA; 
		return self::where('Tmocodi', $code )->get();
	}

	public static function getByTipo( $tipo )
	{
		return self::where('TmoInSa', $tipo )->get();
	}

	public static function getByCode($code)
	{
		return substr($code,0,1);
	}

  public static function isSalidaOrdenCompra($id)
  {
    return optional(self::where('TmoNomb', "ORDEN DE COMPRA")->first())->Tmocodi == $id;
  }

	public function default()
	{
		return $this->TmoNomb === self::DEFAULT_TIPO;
	}

	public static function getCompra($isNC = false)
	{
		$code = $isNC ? self::NC_COMPRA : self::DEFAULT_INGRESO; 
		return self::where('Tmocodi', $code )->get();
	}

	public function repository()
	{
		return new TipoMovimientoRepository($this);
	}

}
