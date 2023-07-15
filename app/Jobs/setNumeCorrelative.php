<?php

namespace App\Jobs;

use App\Resumen;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class setNumeCorrelative
{
	use Dispatchable;

	protected $resumen;

	public function __construct(Resumen $resumen)
	{
		$this->resumen = $resumen;
	}

	public function handle()
	{
		$docNumeParcial  =  $this->resumen->getDocNumeParcial();

		$resumenLast = Resumen::OrderByDesc('DocNume')
			->where("DocNume", 'LIKE', $docNumeParcial . '%')
			->first();


		$correDia = Resumen::DOC_DIA_INIT;
		
		if ( $resumenLast && optional($resumenLast)->hasNewFormatCorrelative() ) {
			# Obtener el correlativo del ultimo resumen enviado y sumarle uno
			$correDia = $resumenLast->getCorrelativeDia(true) + 1;
			# Agregar los ceros correspondientes
			$correDia = math()->addCero($correDia, 3);
		}

		$docNume = $docNumeParcial . $correDia;

		$this->resumen->fill(['DocNume' => $docNume ]);
	}
}
