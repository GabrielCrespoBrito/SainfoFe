<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaResumen extends Model
{
	protected $table = "ventas_ra_detalle";
	protected $primaryKey = "numoper";
	protected $keyType = "string";	
  const ID_INIT = "000001";
  public $timestamps = false;

  public static function data($id = false)
  {
    set_timezone();

  	$now = date('Y-m-d');
  	$numero_operacion = $id ? ID_INIT : "00001";
  	$fecha_operacion  = $id ? "" : $now;
  	$fecha_generacion  = $id ? "" : $now;  	
  	$fecha_envio = $id ? "" : "";
  	$moneda = $id ? "" : "SOLES";
  	$codigo_operacion = $id ? "" : "00001";
  	$fecha_documento = $id ? "" : $now;
  	$numero_ticket = $id ? "" : "";
  	$resumen_operacion = $id ? "" : "";
  	$estado = $id ? "" : "";  	

		return [
			'numero_operacion' => self::UltimoId(),
			'fecha_operacion' => $fecha_operacion,
			'fecha_envio' => $fecha_envio,
			'moneda' => $moneda,
			'codigo_operacion' => self::UltimoCorrelativo(),
			'fecha_documento' => $fecha_documento,  			
			'fecha_generacion' => $fecha_generacion,  						
			'numero_ticket' => $numero_ticket,  			
			'resumen_operacion' => $resumen_operacion,  			
			'estado' => $estado,
		];
  }


	public static function agregate_cero( $numero = false  , $set = 0 )
	{
		$numero = $numero ? $numero->numoper : self::ID_INIT;		
		$cero_agregar = [null,"00000","0000","000","00","0"];
		$codigoNum = ((int) $numero) + $set;
		$codigoLen = strlen((string) $codigoNum);

		return $codigoLen < 6 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
	}   

  public static function UltimoId()
  {
  	$ultima_resumen = self::OrderByDesc("numoper")->first();  	

  	return self::agregate_cero( $ultima_resumen , 1 );
  }


	public static function itemDia()
	{
    set_timezone();

  	$fecha = "RC-" . date('Ymd-');
		
  	$a = self::OrderByDesc('docNume')->where("docNume" , 'LIKE' ,  $fecha . '%' )->first();

  	if( $a ){  	
  		$item = ((int)$a->DetItem ) + 1 ;  
  		return ($item <= 9) ? ("0" . $item) : $item;
  	}

  	return "01";

	}

  public static function UltimoCorrelativo()
  {
    set_timezone();
    
  	$fecha = "RC-" . date('Ymd-');
  	// return $fecha;
  	$ultimo_correlativo = self::OrderByDesc('docNume')->where("docNume" , 'LIKE' ,  $fecha . '%' )->first();

  	if( $ultimo_correlativo ){
  		$codigo = $ultimo_correlativo->docNume;
  		$ultimoSecuencia = explode( "-" , $codigo )[2];
  		$codigoInt = (int) $ultimoSecuencia;
  		$nuevoCodigo = $codigoInt < 9 ? "0" . ++$codigoInt : ++$codigoInt ;
  		return ($fecha . $nuevoCodigo);
  	}

  	return $fecha . '01';
  }

}
