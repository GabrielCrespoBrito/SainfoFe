<?php 

namespace App\Models\Cotizacion\Relationship;

use App\Cotizacion;
use App\Producto;
use App\Unidad;

trait CotizacionItemRelationship
{
	public function unidad(){
		return $this->belongsTo( Unidad::class , 'UniCodi' , 'Unicodi' )->where( 'empcodi', $this->EmpCodi );
	}

	public function cotizacion()
	{
		return $this->belongsTo( Cotizacion::class, 'CotNume' , 'CotNume' );
	}

  public function producto(){

		return $this->hasOneThrough(
			Producto::class,
			Unidad::class,
			'Unicodi',
			'ID',
			'UniCodi',
			'Id'
		);
		// ->withoutGlobalScope('noEliminados');
  }

}
