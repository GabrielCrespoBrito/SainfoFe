<?php

namespace App\Jobs\Setting;

use App\Repositories\SettingSystemRepository;
use App\SettingSystem;
use App\Util\ConsultTipoCambio\ConsultTipoCambioInterface;
use App\Util\ConsultTipoCambio\ConsultTipoCambioMigo;

/**
 * Clase para obtener el ultimo de cambio de la sunat, si esta almacenado en el sistema,consultar y ponerlo
 * 
 */
class GetCurrentTipoCambioSunat
{
	public $fecha = 0;
	public $compra;
	public $venta;

	# Consultar tipo de cambio
	public $consulter;

	public $setting_system;

	# Ultimo tipo de cambio en el sistema
	public $latests_tc_system;

	# Valor del ultimo tipo de cambio guardado en el sistema
	public $tc_system_value = null;




	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->consulter = new ConsultTipoCambioMigo();
		$this->compra = 0;
		$this->venta = 0;
		$this->setting_system = new SettingSystem();
		$this->latests_tc_system = $this->getCurrentTipoCambioSystem();
		$this->tc_system_value = $this->getTipoCambioSystemValue();
	}

	/**
	 * Obtener el tipo de cambio actual
	 *
	 * @return null|Model
	 */
	public function getCurrentTipoCambioSystem()
	{
		return $this->setting_system->repository()->all()->where('name','tc_sunat')->first();
	}

	/**
	 * Obtener el tipo de cambio actual
	 *
	 * @return null|object
	 */
	public function getTipoCambioSystemValue()
	{
		return json_decode(optional($this->latests_tc_system)->value);
	}

	/**
	 * Obtener el tipo de cambio actual
	 *
	 * @return void
	 */
	public function setCurrentTipoCambioSystem( $fecha, $compra = 0, $venta = 0)
	{
		$tc = SettingSystem::firstOrCreate(['name' => 'tc_sunat']);

		$tc->value = json_encode([
			'fecha' => $fecha,
			'precio_compra' => $compra,
			'precio_venta' => $venta,
		]);

		$tc->save();

		cache()->forget($this->setting_system->repository()->getKey('all'));
	}

	
	/**
	 * Si hace falta hacer nuevamente una consulta a la sunat para actualizar el tipo de cambio
	 *
	 * @return boolean
	 */
	public function needNewConsult()
	{
		return  $this->tc_system_value === null;
	}


	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function consult()
	{
		# Si necesita nueva consulta hacerla, osea no hay ningun tipo de cambio guardado en el sistema
		if( $this->needNewConsult()){
			$tipo_cambio_sunat = $this->consulter->consult();			
			# Si la consulta fue exitosa
			if( $tipo_cambio_sunat['success'] ){
				$data = $tipo_cambio_sunat['data'];
				$this->setCurrentTipoCambioSystem( $data->fecha, $data->precio_compra, $data->precio_venta );
				$this->setTipoCambioValues($data->fecha, $data->precio_compra, $data->precio_venta);
			}

			# Si no fue exitosa	
			else {
				$this->setTipoCambioValues( '0000-00-00' , 0, 0);
			}
		}

		else {
			$this->setTipoCambioValues( $this->tc_system_value->fecha, $this->tc_system_value->precio_compra, $this->tc_system_value->precio_venta);
		}

		return $this->response();
	}


	public function setTipoCambioValues($fecha, $compra, $venta)
	{
		$this->fecha  = $fecha;
		$this->compra = $compra;
		$this->venta  = $venta;
	}

	public function response()
	{
		return [
			'fecha' => $this->fecha,
			'compra' => $this->compra,
			'venta' => $this->venta
		];
	}
}
