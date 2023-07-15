<?php

namespace App\Http\Middleware;
use App\Grupo;
use Closure;

class FamiliaProductoMiddleware
{
  public function handle($request, Closure $next)
  {
    if( ! Grupo::count() ){      
      $name_route = \Route::currentRouteName();
      $msj = strpos( $name_route, "familia") !== false ? "Tienen que registrar un grupo para poder registrar una familia" : "Tienen que registrar un grupo para poder registrar un producto"; 
      notificacion('Acceso denegado', $msj , 'error');      
      return redirect()->route('grupos.index', ['create' => 1 ]);
    }

    return $next($request);
  }
}
