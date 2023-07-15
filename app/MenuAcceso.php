<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuAcceso extends Model
{
  protected $table       = 'menu_acceso';  
  protected $fillable = ["usuCodi","empCodi","menCodi","priCodi"];

}