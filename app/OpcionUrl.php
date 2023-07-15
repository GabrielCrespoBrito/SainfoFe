<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpcionUrl extends Model
{
  protected $table       = 'opciones_url';  
  public $timestamps   = false;
  protected $primaryKey = 'ID';
  protected $keyType = 'string';
  protected $fillable = ["Nomb"];

}
