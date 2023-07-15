<?php 

namespace App\Models\Contingencia\Relationship;

use App\Models\Contingencia\ContingenciaDetalle;

/**
 * 
 */
trait ContingenciaRelationship
{
	public function detalles()
	{
		return $this->hasMany(ContingenciaDetalle::class, 'con_id' , 'id' );
	}
	
}



