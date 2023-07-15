<?php

namespace App\ModuloMonitoreo\StatusCode;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class StatusCode extends Model
{
  use UsesSystemConnection;
  
  protected $table = "monitor_status_codes";
  protected $fillable = [
    'status_code',
    'status_message',
    'tipo'
  ];
  public $timestamps = false;

  const CODE_EXITO_0001 = "0001"; 
  const CODE_EXITO_0002 = "0002"; 
  const CODE_EXITO_0003 = "0003"; 
  const CODE_ERROR_0004 = "0004"; 
  const CODE_ERROR_0005 = "0005"; 
  const CODE_ERROR_0006 = "0006"; 
  const CODE_ERROR_0007 = "0007"; 
  const CODE_ERROR_0008 = "0008"; 
  const CODE_ERROR_0009 = "0009"; 
  const CODE_ERROR_0010 = "0010"; 
  const CODE_ERROR_0011 = "0011"; 
  const CODE_ERROR_0012 = "0012";

  /**
   * El comprobante existe y está aceptado  
   *  */ 
  const CODE_0001 = "0001";
  /**
   * El comprobante existe  pero está rechazado  
   *  */ 
  const CODE_0002 = "0002";
  /**
   * El comprobante existe pero está de baja  
   *  */   
  const CODE_0003 = "0003";
  const CODE_0004 = "0004";
  const CODE_0005 = "0005";
  const CODE_0006 = "0006";
  const CODE_0007 = "0007";
  const CODE_0008 = "0008";
  const CODE_0009 = "0009";
  const CODE_0010 = "0010";
  /**
   * El comprobante de pago electrónico no existe  
   *  */    
  const CODE_0011 = "0011";
  /**
   * El comprobante de pago electrónico no le pertenece  
   *  */      
  const CODE_0012 = "0012"; 


  
  const EXITO_0001 = [ 'code' => '0001', 'descripcion' => 'El comprobante existe y está aceptado'];
  const EXITO_0002 = [ 'code' => '0002', 'descripcion' => 'El comprobante existe  pero está rechazado'];
  const EXITO_0003 = [ 'code' => '0003', 'descripcion' => 'El comprobante existe pero está de baja' ];
  const ERROR_0004 = [ 'code' => '0004', 'descripcion' => 'Formato de RUC no es válido (debe de contener 11 caracteres ,numéricos)'];
  const ERROR_0005 = [ 'code' => '0005', 'descripcion' => 'Formato del tipo de comprobante no es válido (debe de contener ,2 caracteres)'];
  const ERROR_0006 = [ 'code' => '0006', 'descripcion' => 'Formato de serie inválido (debe de contener 4 caracteres)'];
  const ERROR_0007 = [ 'code' => '0007', 'descripcion' => 'El numero de comprobante debe de ser mayor que cero'];
  const ERROR_0008 = [ 'code' => '0008', 'descripcion' => 'El número de RUC no está inscrito en los registros de la SUNAT'];
  const ERROR_0009 = [ 'code' => '0009', 'descripcion' => 'EL tipo de comprobante debe de ser (01, 07 o 08)'];
  const ERROR_0010 = [ 'code' => '0010', 'descripcion' => 'Sólo se puede consultar facturas, notas de crédito y debito, electrónicas, cuya serie empieza con "F'];
  const ERROR_0011 = [ 'code' => '0011', 'descripcion' => 'El comprobante de pago electrónico no existe'];
  const ERROR_0012 = [ 'code' => '0012', 'descripcion' => 'El comprobante de pago electrónico no le pertenece'];
  
  const CODES = [
    self::EXITO_0001['code'] => self::EXITO_0001['descripcion'],
    self::EXITO_0002['code'] => self::EXITO_0002['descripcion'],
    self::EXITO_0003['code'] => self::EXITO_0003['descripcion'],
    self::ERROR_0004['code'] => self::ERROR_0004['descripcion'],
    self::ERROR_0005['code'] => self::ERROR_0005['descripcion'],
    self::ERROR_0006['code'] => self::ERROR_0006['descripcion'],
    self::ERROR_0007['code'] => self::ERROR_0007['descripcion'],
    self::ERROR_0008['code'] => self::ERROR_0008['descripcion'],
    self::ERROR_0009['code'] => self::ERROR_0009['descripcion'],
    self::ERROR_0010['code'] => self::ERROR_0010['descripcion'],
    self::ERROR_0011['code'] => self::ERROR_0011['descripcion'],
    self::ERROR_0012['code'] => self::ERROR_0012['descripcion'],
  ];

  public static function getCodesPrincipales()
  {
      return [
      self::EXITO_0001['code'] => self::EXITO_0001['descripcion'],
      self::EXITO_0002['code'] => self::EXITO_0002['descripcion'],
      self::EXITO_0003['code'] => self::EXITO_0003['descripcion'],
      self::ERROR_0011['code'] => self::ERROR_0011['descripcion'],
    ];
  }

  public static function getNombreByCode($code)
  {
    $nombre = self::CODES[$code];

    return $nombre;
  }

  public function getColor()
  {
    return "success";
  }

  public static function getByCode($code)
  {
    $code = (int) $code;
    $code = is_int($code) ? math()->addCero($code,4) : $code;
    
    return self::CODES[$code];
  }
}