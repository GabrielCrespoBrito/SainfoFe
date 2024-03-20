<?php

namespace App\Models\Suscripcion;

use App\Empresa;
use App\Venta;
use Carbon\Carbon;
use App\Suscripcion\SuscripcionUso;
use App\Jobs\Suscripcion\CreateUsos;
use App\Jobs\UpdateConsumoSuscripcion;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use App\Models\Empresa\Traits\SuscripcionInteract;

class Suscripcion extends Model
{
	use UsesSystemConnection,
	SuscripcionInteract;

	protected $table = "suscripcion_system_suscripciones";
	protected $fillable = [
		'empresa_id',
		'orden_id',
		'fecha_inicio',
		'fecha_final',
		'estatus',
    'actual'
	];

	const ACTIVA = "activa";

	const ESTATUS_ACTIVA = "activa";
	const ESTATUS_VENCIDA = "vencida";
	const ESTATUS_CANCELADA = "cancelada";

	const ACTUAL = 1;
	const ANTERIOR = 0;
	

	public function orden()
	{
		return $this->belongsTo( OrdenPago::class, 'orden_id' );
	}

	public function createFromOrden( OrdenPago $orden_pago )
	{
		$planduracion = $orden_pago->planduracion;

		$this->orden_id = $orden_pago->id;
		// $this->fecha_inicio');
		$this->empresa_id = $orden_pago->empresa_id;

		// Existe
		if( $this->exists ){
			// $fecha_final = new Carbon($this->fecha_final);
			$fecha_final = $planduracion->getFechaVencimiento();
		}

		else {
			$fecha_final = $planduracion->getFechaVencimiento();
		}

		$this->fecha_final = $fecha_final;
		$this->estatus = Suscripcion::ACTIVA;
		$this->save();
	}

	/**
	 * Fecha donde termina la suscripción actual
	 *
	 * @return boolean
	 */
	public function getEndDateSuscripcion()
	{
		return $this->fecha_final;
	}

	/**
	 * Crear usos de utilización para la suscripción
	 */

	public function createUsos()
	{
		CreateUsos::dispatchNow($this);
	}

	/**
	 * Usos de la suscripcion
	 * @return HasMany
	 */
	public function usos()
	{
		return $this->hasMany( SuscripcionUso::class, 'suscripcion_id' );
	}

	public function initConsumo(  )
	{
		foreach( $this->usos as $uso ){
			if( $uso->isResetMensual() ){
				$uso->initConsumo();
			}
		}
	}

	public function getUso( $caracteristica_name )
	{
		foreach( $this->usos as $uso ){
			if( strtolower($uso->caracteristica->codigo) == strtolower($caracteristica_name) ){
				return $uso;
			}
		}
	}
  
	/**
	 * Undocumented function
	 *
	 * @param [type] $caracteristica_name
	 * @param boolean $add
	 * @return void
	 */ 
	public function sumarRestarConsumo( $caracteristica_name , $cantidad = 1, $sumar = true )
	{
		$uso = $this->getUso($caracteristica_name);
		$sumar ? $uso->sumarUso($cantidad) : $uso->restarUso($cantidad);
	}

	public function consumoMaximo($caracteristica_name)
	{
		return $this->getUso($caracteristica_name)->consumoMaximo();
	}

	public function excedeConsumo($caracteristica_name, $cantidad = 1)
	{
		return $this->getUso($caracteristica_name)->excedeConsumo( $cantidad );
	}

	public function getDuracionNombre()
	{
		return $this->orden->planduracion->duracion->getNombre();
	}

	public function inactivaStatus()
	{
		$this->update([ 'estatus' => self::ESTATUS_VENCIDA  ]);
	}


	public function empresa()
	{
		return $this->belongsTo( Empresa::class, 'empresa_id' , 'empcodi' );
	}

  public function updateFecha($fecha)
  {
    $this->update([ 'fecha_final' => $fecha ]);
    $this->empresa->updateTiempoSuscripcion( $fecha );
    return $this;
  }

  public function updateConsumo($caracteristica)
  {
    (new UpdateConsumoSuscripcion($this,$caracteristica))->handle();
  }

  
}