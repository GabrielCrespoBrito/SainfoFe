<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrata extends Model
{
	public $fillable = ["nombre","contenido"];
	
	public function documentos(){
		return $this->hasMany( ContrataEntidad::class );
	}

}
