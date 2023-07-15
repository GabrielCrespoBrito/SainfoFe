<?php
namespace App\Models\TomaInventario\Attributes;


/**
 * 
 */
trait TomaInventarioAttribute
{
  public function getIdAttribute()
  {
    return $this->InvCodi;
  }
  
}
