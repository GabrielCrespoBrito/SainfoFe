<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaConsultaSunat extends Model
{
  protected $connection = "mysql";
  protected $table = "ventas_consultas_sunat";
  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FCrea";
  public $fillable = [ "VtaOper" , "CodiSunat" , "Descripcion" , "User_Crea" ];

  public static function createRegistro( $venta , $respuesta )
  {
    $data = [
      'VtaOper'    =>  $venta->VtaOper,
      'CodiSunat'  =>  $respuesta->statusCdr->statusCode,
      'Descripcion'=>  $respuesta->statusCdr->statusMessage,      
      'User_Crea'  =>  auth()->user()->usulogi,
    ];
    self::create($data);
  }

  public function updateConsulta( $respuesta ){
    $data = [
      'CodiSunat'  =>  $respuesta->statusCdr->statusCode,
      'Descripcion'=>  $respuesta->statusCdr->statusMessage,      
      'User_Modi'  =>  auth()->user()->usulogi,            
    ];
    self::update($data);
  }
}
