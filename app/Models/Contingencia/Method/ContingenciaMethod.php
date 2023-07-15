<?php 

namespace App\Models\Contingencia\Method;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Models\Contingencia\ContingenciaDetalle;

trait ContingenciaMethod 
{

	public function hasTicket()
	{
		return (bool) $this->ticket;
	}

	public static function create( $fecha )
	{
		// $fecha_split = explode("-", $fecha);
		$fechaFormat = str_replace("-", "", $fecha);
		$numeracion = self::getCorrelative($fecha);

		$docume = str_concat('-', self::IDENTIFICACION, $fechaFormat , $numeracion);
		$contingencia = new self();
		$contingencia->empcodi = empcodi();
		$contingencia->panano = date('Y');
		$contingencia->panperi = date('m');
		$contingencia->mescodi = date('Ym');
		$contingencia->docnume = $docume;
		$contingencia->fecha_emision = now();
		$contingencia->fecha_documento = $fecha;
		$contingencia->save();

		return $contingencia;
	}

	public static function getCountByDate($fecha)
	{
		return self::where('empcodi' , empcodi())
		->where('fecha_documento' , $fecha )
		->count();
	}


	public static function getCorrelative($fecha)
	{
		$quantity = self::getCountByDate( $fecha );
		$newCorrelative = $quantity+1;
		return $newCorrelative < 10 ? ("0".$newCorrelative) : $newCorrelative;
	}

	public function createDetalle( $detalles )
	{
		foreach( $detalles as $detalle ){
			ContingenciaDetalle::createFromDocument( $detalle['vtaoper'] , $detalle['motivo'] , $this );
		}
	}

	public function deleteDetalles()
	{
	  foreach( $this->detalles as $detalle ){

	     $detalle->documento->update([
	     	'fe_rpta' => 9,
	     	'fe_rptaa' => 2,
				 'VtaFMail' => StatusCode::ERROR_0011['code'],
	     ]);

	     $detalle->delete();
	  }	
	}

}