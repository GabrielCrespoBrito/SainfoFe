<?php

namespace App\Models\Caja\Scope;


trait CajaScope
{

  public function scopeUsucodi($query, $value)
  {
    return $query->where('UsuCodi', $value);
  }

  public function scopeLocCodi($query, $value)
  {
    return $query->where('LocCodi', $value);
  }  

  public function scopeCueCodi($query, $value)
  {
    return $query->where('CueCodi', $value);
  }

  public function scopeMesCodi($query, $value)
  {
    return $query->where('MesCodi', $value);
  }

  public function scopeUltima($query){
    return $query->orderByDesc('MesCodi')->first();
  }
}
