<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
  protected $table       = 'provincia';  
  protected $timetamps   = false;
  protected $primaryKey = 'provcodi'; 
  protected $keyType = 'string';   

  public function distritos(){
  	return $this->hasMany(Distrito::class, 'provcodi' , 'provcodi');
  }

}
