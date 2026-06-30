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

	public function getBancoImageLogo()
	{
		$nameBanco = mb_strtolower(trim($this->bannomb));

		$path = public_path(file_build_path('images', 'bancos', $nameBanco . '.jpeg'));

		if (file_exists($path)) {
			return $path;
		}

		return false;
	}
	
}
