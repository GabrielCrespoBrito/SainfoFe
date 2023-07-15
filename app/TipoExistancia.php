<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoExistancia extends Model
{
  protected $table       = 'tipo_existencia';  
  protected $primaryKey   = "TieCodi";  
  protected $keyType   = "string";
  protected $timetamps   = false;
}
