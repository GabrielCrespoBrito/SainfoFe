<?php
namespace App\Models\Guia\Traits\Scopes;

use App\Models\Guia\Guia;
use Illuminate\Database\Eloquent\Builder;

trait GuiaIngresoScope 
{
  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('formato', function (Builder $builder) {
      $builder->where('EntSal', '=', Guia::INGRESO);
    });

  }


}