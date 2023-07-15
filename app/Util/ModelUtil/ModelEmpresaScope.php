<?php

namespace App\Util\ModelUtil;


trait ModelEmpresaScope
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('empresa', function ($query) {
      $empcodiField = self::EMPRESA_CAMPO ?? 'empcodi';
      return $query->where( $empcodiField , empcodi() );
    });
  }  
}


