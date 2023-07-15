<?php
namespace App\Models\User\Attribute;


trait UserAttribute
{
  public function getDireccionAttribute()
  {
    return $this->usudire;
  }

  public function getNombreAttribute()
  {
    return $this->usunomb;
  }

  public function getTelefonoAttribute()
  {
    return $this->usutele;
  }

}