<?php 

namespace App\Models\Venta\Relationship;

use App\Unidad;
use App\TipoIgv;

trait VentaItemRelationship
{

	public function unidad(){
		return $this->belongsTo( Unidad::class , 'UniCodi' , 'Unicodi' )->where( 'empcodi', $this->EmpCodi );
	}

	public function tipoigv()
	{
		return $this->belongsTo( TipoIgv::class, 'TipoIGV' , 'cod_sunat' );
	}
	

}
