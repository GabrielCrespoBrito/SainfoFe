<?php

namespace App\Models\Empresa\Traits;

use App\Models\Suscripcion\Plan;
use Illuminate\Support\Carbon;

trait SuscripcionInteract 
{ 
	public function diasRestante()
	{
		$dias = config('app.recordario_vencimiento_suscripcion');
		$fechaVencimiento = new Carbon($this->getEndDateSuscripcion());
		$fechaVencimiento->endOfDay();
		
		$fechaActual = new Carbon();

		return $fechaActual->diffForHumans($fechaVencimiento);
	}

	public function diasRestanteNumber()
	{
		$dias = config('app.recordario_vencimiento_suscripcion');
		$fechaVencimiento = new Carbon($this->getEndDateSuscripcion());
		$fechaVencimiento->endOfDay();

		$fechaActual = new Carbon();

		return $fechaActual->diffInDays($fechaVencimiento);
	}

	/**
	 * Ver si la suscripción de la empresa donde esta el usuario, esta activa
	 *
	 * @return boolean
	 */	
	public function isSuscripcionActive()
	{
		$fechaVencimiento = new Carbon($this->getEndDateSuscripcion());
		$fechaActual = new Carbon();

		return $fechaVencimiento->isAfter($fechaActual);
	}

	/**
	 * Obtener información sobre la fecha de la suscripción
	 *
	 * @return object
	 */
	public function getSuscripcionDates()
	{
		$dias_recordatorio_porvencer = config('app.recordatorio_suscripcion_porvencerse');
		$dias_recordatorio_vencimiento = config('app.recordatorio_suscripcion_porvencida');
		$fechaActual = new Carbon();
		$fechaVencimiento = new Carbon($this->getEndDateSuscripcion());
		$fechaRecordatorio = $fechaVencimiento->copy()->subDays($dias_recordatorio_porvencer);
		$isActive = $fechaVencimiento->isAfter($fechaActual);
		$isFechaRecodatorio = $fechaActual->isSameDay($fechaRecordatorio);
		$isFechaVencimiento = $fechaActual->isSameDay($fechaVencimiento);
		$isVencida = $fechaActual->isAfter($fechaVencimiento);
    $isSafeTime = $isActive && $fechaRecordatorio->isAfter($fechaActual);   
		$isFechaRecordatorioVencimiento = !$isActive && $fechaActual->isSameDay($fechaVencimiento->copy()->addDays($dias_recordatorio_vencimiento));
		$diasParaVencimiento = $fechaActual->diffForHumans($fechaVencimiento);

		$data = (object) [
			'fechaActual' => $fechaActual, 
			'fechaVencimiento' => $fechaVencimiento, 
			'fechaRecordatorio' => $fechaRecordatorio,
			'isActive' => $isActive,
			'isSafeTime' => $isSafeTime,
      // Si hoy es la fecha del recordatorio por email 
			'isFechaRecodatorio' => $isFechaRecodatorio, 
      //Si esta en la fecha de recordatorio del vencimineto
			'isFechaRecordatorioVencimiento' => $isFechaRecordatorioVencimiento, 
      // Si es la fecha de vencimiento
			'isFechaVencimiento' => $isFechaVencimiento, 
      // 
			'isVencida' => $isVencida, 
			'diasParaVencimiento' => $diasParaVencimiento, 
		];


		return $data;
	}

	/**
	 * Si la esta por vencerse 
	 * 
	 * @return bool
	 */
	public function isSuscripcionPorVencerse()
	{
		$dias = config('app.recordario_vencimiento_suscripcion');
		
		$fechaActual = new Carbon();
		$fechaVencimiento = new Carbon($this->getEndDateSuscripcion());
		$fechaRecordatorio = clone ($fechaVencimiento);
		$fechaRecordatorio = $fechaRecordatorio->subDays($dias);
		
		return $fechaActual->isAfter($fechaRecordatorio);
	}

	/**
	 * Estamos en el dia de recordatorio de vencimiento de suscripción
	 * 
	 * @return bool
	 */
	public function isFechaRecordatorio()
	{
		$dias = config('app.recordario_vencimiento_suscripcion');
		
		$fechaActual = new Carbon();
		$fechaVencimiento = new Carbon($this->getEndDateSuscripcion());
		$fechaRecordatorio = clone ($fechaVencimiento);
		$fechaRecordatorio = $fechaRecordatorio->subDays($dias);
		return $fechaActual->isSameDay($fechaRecordatorio);
	}

	/**
	 * Si la esta por vencerse 
	 * 
	 * @return bool
	 */
	public function isSuscripcionVencida()
	{
		$fechaVencimiento = new Carbon($this->getEndDateSuscripcion());
		$fechaActual = clone ($fechaLimite = new Carbon());
		return $fechaActual->isAfter($fechaVencimiento);
	}

	/**
	 * Si la esta por vencerse 
	 * 
	 * @return bool
	 */

	public function isSuscripcionPorEliminarse()
	{
		return true;
	}

	/**
	 * Si la empresa esta en la suscripcion demo
	 */
	public function isSuscripcionDemo()
	{
	  return $this->tipo_plan == Plan::TIPO_DEMO;
	}

	/**
	 * Poner los valores reales para cada uso
	 */
	public function updateUsos()
	{
		$suscripcion = $this->suscripcionActual();
		foreach( $suscripcion->usos as $uso )
		{
			$uso->updateRealUso();
		}
	}
}