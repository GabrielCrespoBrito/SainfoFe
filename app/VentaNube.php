<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaNube extends Model
{
  public $table = "ventas_nube";
  const CREATED_AT = "User_FCrea";
  public $fillable = ['XML','PDF','CDR','VtaOper','Estatus'];
  public $timestamps = false;

  public function venta()
  {
    return $this->belongsTo( Venta::class, 'VtaOper' , 'VtaOper' );
  }

  public static function setEstatus(){
    foreach( self::all() as $venta_nube ){

      if( $venta_nube->XML && $venta_nube->PDF && $venta_nube->CDR ){
        $venta_nube->update(['Estatus' => 0]);
      }
      else if( !$venta_nube->XML && !$venta_nube->PDF && !$venta_nube->CDR ){
        $venta_nube->update(['Estatus' => 2]);
      }
      else {
        $venta_nube->update(['Estatus' => 1]);
      }
    }
  }

  

}
