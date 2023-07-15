<?php

namespace App\Http\Middleware;
use Closure;
use App\Caja;
use App\M;

class CajaMiddleware
{
  public function handle($request, Closure $next)
  { 
    if( ! Caja::hasAperturada(null, get_empresa()->isTipoCajaLocal() ) ){

      notificacion('Caja sin aperturar' , 'Es necesario aperturar la caja'  , 'error' );
      return redirect()->route('cajas.index');
    }
    return $next($request);
  }
}
