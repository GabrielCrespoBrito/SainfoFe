<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentosPendienteDetalle extends Model
{
  protected $table = "documentos_pendientes_detalle";  
  public $timestamps   = false;
  public $fillable = ["id_documento_pendiente" , "VtaOper" , "VtaNume"];

  public function venta()
  {
    return $this->belongsTo( Venta::class, 'VtaOper' , 'VtaOper' );
  }

}
