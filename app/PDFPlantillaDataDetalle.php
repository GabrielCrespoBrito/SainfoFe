<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class PDFPlantillaDataDetalle extends Model
{
  use UsesSystemConnection;
  protected $table = "pdf_plantillas_data_detalle";
  public $guarded = [];
  public $timestamps = false;
  
  public function getDetItemAttribute()
  {
    return $this->item;
  }  

  public function getDetCodiAttribute()
  {
    return $this->codigo_producto;
  }

  public function getDetUnidAttribute()
  {
    return $this->unidad;
  }

  public function getDetNombAttribute()
  {
    return $this->nombre_item;
  }

  public function getDetCantAttribute()
  {
    return $this->cantidad;
  }

  public function precioUnitario()
  {
    return $this->precio;
  }

  public function valorUnitario()
  {
    return $this->precio;
  }

  

  public function getDetPesoAttribute()
  {
    return 0;
  }


  public function getDetImpoAttribute()
  {
    return $this->importe;
  }

  public function getTotal()
  {
    return $this->importe;
  }



}