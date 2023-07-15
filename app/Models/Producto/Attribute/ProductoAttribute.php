<?php 

namespace App\Models\Producto\Attribute;

trait ProductoAttribute
{
	public function unidadPrincipal(){
		return $this->unidades->first();
	}
  public function setProCodi1Attribute( $value ){


    if( $value == null || strtoupper(trim($value)) == "" ){
      $this->attributes['ProCodi1'] = null;
      return;
    }

    $this->attributes['ProCodi1'] = strtoupper(trim($value));
    return;

  }
	
}
