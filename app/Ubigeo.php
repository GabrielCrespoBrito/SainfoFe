<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Ubigeo extends Model
{
  use UsesSystemConnection;
	protected $table = "ubigeo";
	protected $primaryKey = "ubicodi";
	protected $keyType = "string";
	
	public function departamento(){
		return $this->belongsTo( Departamento::class, 'depcodi' , 'depcodi' );
	}

	public function provincia(){
		return $this->belongsTo( Provincia::class, 'provcodi' , 'provcodi' );
	}

	public function completeName()
	{
		return sprintf("(%s) %s - %s - %s",
			$this->ubicodi,
			$this->departamento->depnomb,
			$this->provincia->provnomb,
			$this->ubinomb
		);
	}





}
