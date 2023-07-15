<?php

namespace App\Jobs\Resumen;

use App\Resumen;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCDR
{
	use Dispatchable;

	protected $resumen;
	protected $content;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Resumen $resumen,  $content)
	{
		$this->resumen = $resumen;
		$this->content = new ContentCDR( $content, $resumen->getNameFileCDRZip(), $resumen->getNameFileCDRXML() );
	}

	public function handle()
	{
		$respuesta = $this->content
		->saveTemp()
		->saveCDR()
		->extraerContent(["cbc:Description", "cbc:ResponseCode"]);

		$descripcion = $respuesta[0];
		$code = $respuesta[1];
		$codeInt = (int) $code;
		if( $codeInt == 0 || $codeInt > 4000 ){
			$this->resumen->isAnulacion() ?
				$this->resumen->saveSuccessAnulacion($code , $descripcion ) :
				$this->resumen->saveSuccessValidacion($code);
		}
		else {
			$this->resumen->saveError($code , $descripcion);
		}
	}
}