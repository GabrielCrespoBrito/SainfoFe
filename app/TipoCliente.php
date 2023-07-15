<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
  protected $table       = 'prov_clientes_tipo';  
  protected $timetamps   = false;
  protected $primaryKey = 'TippCodi'; 
  protected $keyType = 'string'; 
}
