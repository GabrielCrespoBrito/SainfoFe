<?php

namespace App\Suscripcion;

use App\Models\Suscripcion\Caracteristica;
use App\Models\Suscripcion\Suscripcion;
use App\Producto;
use App\User;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class SuscripcionUso extends Model
{
	use UsesSystemConnection;

	protected $table = "suscripcion_system_suscripciones_usos";

	public $fillable = [
		'caracteristica_id',
		'suscripcion_id',
		'limite',
		'uso',
		'restante',
	];

	public function suscripcion()
	{
		return $this->belongsTo(Suscripcion::class, 'suscripcion_id');
	}

	public function caracteristica()
	{
		return $this->belongsTo(Caracteristica::class,  'caracteristica_id');
	}

	public function updateValues($uso, $restante, $limite = null)
	{
		$this->update([
			'uso' => $uso,
			'restante' => $restante,
			'limite' => $limite ?? $this->limite,
		]);
	}

	public function sumarRestarUso($quantity = 1, $sumar = true)
	{
		$uso    = $this->uso;
		$limite = $this->limite;

		if ($sumar) {
			$uso += $quantity;
			$restante = $limite - $uso;
		}
		else {
			$uso -= $quantity;
			$restante = $limite + $uso;
		}

		$this->updateValues($uso,$restante);
	}

	public function initConsumo()
	{
		$this->updateValues( 0, $this->limite);
	}


	public function sumarUso($quantity = 1)
	{
		$this->sumarRestarUso($quantity, true);
	}

	public function restarUso($quantity = 1)
	{
		$this->sumarRestarUso($quantity, false);
	}

	/**
	 * Si ya se llego a la cantidad maxima permitida
	 *
	 * @return bool
	 */
	public function consumoMaximo()
	{
		return $this->restante <= 0;
	}

	/**
	 * Si con la cantidad suministrada se excede el uso maximo 
	 *
	 * @return boolean
	 */
	public function excedeConsumo($cantidad = 1)
	{
		return ($this->uso + $cantidad) > $this->limite;
	}

	public function isResetMensual()
	{
		return $this->caracteristica->isReset();
	}


	public function isTipoConsumo()
	{
		return $this->caracteristica->isTipoConsumo();
	}

	/**
	 * Actualizar uso que ha tenido dependiendo de la caracteristica
	 * 
	 * @return void
	 */
		 
	public function updateRealUso()
	{
		$caracteristica = $this->caracteristica;
		$empresa = $this->suscripcion->empresa;

		if(  !$caracteristica->isTipoConsumo() ){
			return;
		}

		$limite = $this->limite;


		$inicioMes = now()->firstOfMonth()->format('Y-m-d');
		$finalMes = now()->endOfMonth()->format('Y-m-d');
		

		switch ($caracteristica->codigo) {
			case Caracteristica::COMPROBANTES:
				$uso = $empresa->ventas->count();
				$uso = $empresa->ventas
				->where('User_FCrea' , '>=' , $inicioMes )
				->where('User_FCrea' , '<=' , $finalMes )
				->count();
				break;
			case Caracteristica::USUARIOS:
				$uso = $empresa->users->where('usucodi', '!=' , User::ID_ADMIN )->count();
				break;
			case Caracteristica::PRODUCTOS:
				$uso = $empresa->productos->count();
				break;
			case Caracteristica::LOCAL:
				$uso = $empresa->locales->count();
				break;
			default:
				throw new \Exception("Error {$this->codigo}", 1);
				break;
		}



		// return [ $empresa->empcodi, $inicioMes, $finalMes,  $uso, $limite - $uso];
		$this->updateValues( $uso, $limite-$uso );
	}



}
