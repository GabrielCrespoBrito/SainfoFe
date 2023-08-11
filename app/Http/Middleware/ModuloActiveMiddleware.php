<?php

namespace App\Http\Middleware;

use Closure;

class ModuloActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $modulo )
    {
      if( get_empresa()->getDataAditional($modulo)){
        return $next($request);
      }

      if( $request->isJson() ){
        return response()->json(['message' => 'No tienes Habilitado el Modulo de Producción Manual'], 400);
      }

      noti()->error( 'Acceso denegado' , 'No tienes Habilitado el Modulo de Producción Manual');
      return redirect()->route('home');
    }
}
