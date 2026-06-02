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
		
		if ( $resumenLast  ) {
			
			if( optional($resumenLast)->hasNewFormatCorrelative() ){
				$correDia = $resumenLast->getCorrelativeDia(true) + 1;
				$correDia  = math()->addCero($correDia, 3);
			}
			
			else {
				$docNume = explode( "-", $resumenLast->DocNume ) ;
				$correDia = end($docNume) + 1;
				$correDia = math()->addCero($correDia, 4);
			}
		}

		$docNume = $docNumeParcial . $correDia;

		$this->resumen->fill(['DocNume' => $docNume ]);
	}
}
