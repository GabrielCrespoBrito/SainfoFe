<?php

namespace App\ModuloApi\Models\User;


use Illuminate\Database\Eloquent\Model;
use App\ModuloApi\Models\Empresa\Empresa;
use App\ModuloApi\Models\traits\UseApiConnection;
use App\ModuloApi\Models\UserEmpresa\UserEmpresa;

class User extends Model
{
	use UseApiConnection;
	const DEFAULT_EMPRESA = 1;

	public function getToken()
	{
		return $this->remember_token;
	}

	protected static function getByToken($token)
	{
		return User::where( 'remember_token' , $token )->first();
	}

	public function empresas()
	{
		return $this->belongsToMany(
			Empresa::class,
			'user_empresa'
		);
	}

	/**
	 * Traer la empresa con la que actualmente esta trabajando el usuario
	 *
	 * @return Empresa
	 */
	public function getDefaultEmpresa()
	{
		// @TODO devolver solo la empresa, con la que actualmente esta trabajando el usuario
		return $this->empresas->first();
	}
}
