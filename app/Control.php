<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Control extends Model
{
  use UsesSystemConnection;

  public $table = "control";
  public $primaryKey = "CtoCodi";
  public $keyType = "string"; 
  public $timestamps = false;
  const INGRESO_VENTA = "001";
  const OTROS_INGRESOS = "004";
  const EGRESO_COMPRA = "002";
  const CAJA = "003";
  const OTROS_EGRESOS = "005";
  const RETIRO_EFECTIVO = "006";  
  const PAGO_PERSONAL = "020"; 
  const SALIDA_TRANSFERENCIA = "015"; 
  const ENTRADA_TRANSFERENCIA = "016"; 
  const TRANSFERENCIA_BANCO = "09"; 
  const PERMITIDOS = [
    self::INGRESO_VENTA,
    self::OTROS_INGRESOS,
    self::CAJA,
    self::EGRESO_COMPRA,
    self::OTROS_EGRESOS,
    self::RETIRO_EFECTIVO,
    self::PAGO_PERSONAL,
  ];
  const INGRESOS = [
    self::INGRESO_VENTA,
    self::CAJA,    
    self::OTROS_INGRESOS
  ];
  const EGRESOS = [
    self::EGRESO_COMPRA,
    self::OTROS_EGRESOS,
    self::RETIRO_EFECTIVO,
    self::PAGO_PERSONAL,    
  ];  
  const EGRESO_PERMITIDOS = [
    self::OTROS_EGRESOS,
    self::RETIRO_EFECTIVO,
    self::PAGO_PERSONAL,    
    self::SALIDA_TRANSFERENCIA,  
    self::TRANSFERENCIA_BANCO
  ];

  
  public function isCaja(){
    return $this->CtoCodi == self::CAJA;
  }

  public function isIngresoVenta(){
    return $this->CtoCodi == self::INGRESO_VENTA;
  }

  public function isOtrosIngresos(){
    return $this->CtoCodi == self::OTROS_INGRESOS;
  }

  public function isOtrosEgresos(){
    return $this->CtoCodi == self::OTROS_EGRESOS;
  }


  public static function allUtilizar()
  {    
    return self::all()->filter(function($control){
      return in_array( $control->CtoCodi , self::PERMITIDOS ); 
    });
  }

  public static function getName($type)
  {
    return self::find($type)->CtoNomb;
  }

  public static function isIngreso( $tipo )
  {
    return (self::INGRESO_VENTA == $tipo || self::OTROS_INGRESOS == $tipo);
  }


}
