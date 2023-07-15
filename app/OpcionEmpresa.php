<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpcionEmpresa extends Model
{
	protected $table       = 'opciones_emp';  
  protected $primaryKey = 'UltCpra';  
  protected $timetamps = false;
}