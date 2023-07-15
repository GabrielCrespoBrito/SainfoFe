<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
  protected $table = "zona";
  protected $primaryKey = "ZonCodi";
  protected $keyType = "string";  
  public $timestamps = false;   
  public $incrementing = false; 
  
}
