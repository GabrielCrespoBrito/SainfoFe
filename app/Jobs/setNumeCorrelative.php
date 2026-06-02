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
		
		logger('@RESUMEN-LAST', [ $resumenLast ]);

		if ( $resumenLast  ) {
			
			logger('@RESUMEN-LAST-FORMATO', [ optional($resumenLast)->hasNewFormatCorrelative() ]);

			if( optional($resumenLast)->hasNewFormatCorrelative() ){
				$correDia = $numeracion = $resumenLast->getCorrelativeDia(true);
				logger('@NUMERACION-DEL-DIA' , [ $correDia ]);
				$correDia = $correDia + 1;
				$correDia  = math()->addCero($correDia, 3);
			}
			
			else {
				$docNume = explode( "-", $resumenLast->DocNume ) ;
				$correDia = end($docNume) + 1;
				$correDia = math()->addCero($correDia, 4);

				logger('@FORMATO-ANTERIOR' , [ $correDia ]);
			}
		}

		$docNume = $docNumeParcial . $correDia;

		logger('@RESUMEN-NUMERACION' , [ $this->resumen->EmpCodi, $docNume]);

		$this->resumen->fill(['DocNume' => $docNume ]);
	}
}
