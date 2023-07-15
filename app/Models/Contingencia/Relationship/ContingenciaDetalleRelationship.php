<?php 

namespace App\Models\Contingencia\Relationship;

use App\Models\Contingencia\ContingenciaDetalle;
use App\Models\Contingencia\ContingenciaMotivo;
use App\Venta;

/**
 * 
 */
trait ContingenciaDetalleRelationship
{
	public function motivo()
	{
		return $this->belongsTo(ContingenciaMotivo::class, 'motivo_id' , 'id' );
	}

	public function documento()
	{
		return $this->hasOne(Venta::class, 'VtaOper' , 'vtaoper' );
	}
}



