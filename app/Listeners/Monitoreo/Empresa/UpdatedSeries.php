<?php

namespace App\Listeners\Monitoreo\Empresa;

use App\ModuloMonitoreo\DocSerie\DocSerie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatedSeries
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  object  $event
	 * @return void
	 */
	public function handle($event)
	{
		$ids = [];
		
		$idsToDestroy = array_diff(	$event->empresa->series->pluck('id')->toArray(), $event->request->series_id );

		DocSerie::destroy($idsToDestroy);

		for ($i = 0; $i < count($event->request->series_id); $i++) {
			
			$id = $event->request->series_id[$i];

			$data = [
				'serie' => $event->request->serie[$i],
				'tipo_documento' => $event->request->tipo_documento[$i]
			];

			if ($id == null) {
				$serie = $event->empresa->series()->create($data);
			} else {
				$serie = DocSerie::find($id);
				$serie->update($data);
			}

		}

		
	}
}
