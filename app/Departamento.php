<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
  protected $table       = 'departamento';  
  protected $timetamps   = false;
  protected $primaryKey = 'depcodi'; 
  protected $keyType = 'string'; 

  public function provincias(){
  	return $this->hasMany( Provincia::class , 'depcodi' , 'depcodi' );
  }

  public function distritos(){
  	return $this->hasManyThrough( Distrito::class , Provincia::class, 'depcodi', 'provcodi', 'depcodi');
  }

}
