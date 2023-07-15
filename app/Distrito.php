<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
  protected $table       = 'ubigeo';  
  protected $timetamps   = false;
  protected $primaryKey = 'ubicodi'; 
  protected $keyType = 'string'; 
}
