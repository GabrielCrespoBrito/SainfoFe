<?php 

namespace App\Models\Contingencia\Attribute;

trait ContingenciaAttribute
{
	public function getFechaDocumentoAttribute($value)
	{
		return $this->fecha_documento ?? date('Y-m-d');
	}
	
}


