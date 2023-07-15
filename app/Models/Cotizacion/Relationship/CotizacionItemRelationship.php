<?php 

namespace App\Models\Cotizacion\Relationship;

use App\Unidad;

trait CotizacionItemRelationship
{
	public function unidad(){
		return $this->belongsTo( Unidad::class , 'UniCodi' , 'Unicodi' )->where( 'empcodi', $this->EmpCodi );
	}

}
