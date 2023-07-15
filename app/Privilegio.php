<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
  protected $table       = 'privilegios';  
  protected $timetamps   = false;
  protected $primaryKey = 'privCodi';  
}
