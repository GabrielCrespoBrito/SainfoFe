<?php

namespace App;

use App\BancoEmpresa;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
	protected $primaryKey = "BanCodi";
	protected $keyType = "string";
	
	use UsesSystemConnection;

	public function cuentas()
	{
		return $this->hasMany(BancoEmpresa::class, 'BanCodi' , 'bancodi' );
	}
	
}
