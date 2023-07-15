<?php

namespace App\Http\Middleware;

use Closure;

class IsAdminMiddleware
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
      $user = auth()->user();

      if( !$user->isAdmin() )
      {
        notificacion('Acceso restringido', 'No posee los permisos para ingresar en esta area' , 'error');
        return redirect()->route('home');        
      }

      return $next($request);
    }
}
