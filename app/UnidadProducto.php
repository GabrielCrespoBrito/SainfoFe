<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadProducto extends Model
{
  protected $table       = 'uniproducto';  
  protected $primaryKey   = "UnPCodi";  
  protected $keyType   = "string";
  protected $timetamps   = false;

  public function nombre()
  {

  }

  public function codigo(){
    
  }

}
