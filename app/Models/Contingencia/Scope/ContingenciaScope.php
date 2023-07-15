<?php

namespace App\Models\Contingencia\Scope;

/**
 * 
 */
trait ContingenciaScope
{
	public static function boot()
	{
		parent::boot();
    static::addGlobalScope('empresa', function ($query ) {
      return $query->where('empcodi', empcodi() );
    });

	}
}

