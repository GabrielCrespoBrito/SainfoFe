<?php

namespace App\Models\Contingencia;

use Illuminate\Database\Eloquent\Model;

class ContingenciaMotivo extends Model
{
  protected $connection = 'mysql';
	protected $table = "contingencias_motivos";
	protected $guarded = [];
	public $timestamps = false;
}
