<?php

namespace App\Http\Middleware;

use Closure;

class VerifyIsNotOseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( is_ose() ) {
            notificacion("Acceso restringido",'No se puede acceder a esta sección estan en la ose', "error");
            return back();
        }
        return $next($request);
    }
}
