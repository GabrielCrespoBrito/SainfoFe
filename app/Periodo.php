<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
  protected $table = "anio";
  protected $connection = "mysql";
  const CREATED_AT = 'Pan_dFechaCrea';
  const UPDATED_AT = 'Pan_dFechaModifica';

  protected $fillable = [
    'empcodi',
    'id',
    'Pan_cAnio',
    'Pan_cEstado',
    'Pan_cUserCrea',
    'Pan_cFechaCrea',
    'Pan_cUserModifica',
    'Pan_cFechaModifica',
    'Pan_cEquipoUser',
    'Pan_cPeriodo'
  ];
  
  public static function createDefault($empcodi, $year = null, $active = true )
  {
    $year = $year ?? date('Y');
  	$p = new self();
    $p->id = self::max('id') + 1;
  	$p->empcodi = $empcodi;
  	$p->Pan_cAnio = $year;
  	$p->Pan_cEstado = $active ? 'A' : 'V';  	  	  	
  	$p->save();
  }
}