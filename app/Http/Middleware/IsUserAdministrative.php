<?php

namespace App\Http\Middleware;

use Closure;

class IsUserAdministrative
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
      if ( ! auth()->user()->isAdministrative() ) {
        notificacion('Acceso restringido', 'No puede ingresar en esta area', 'error');
        auth()->logout();
        return redirect()->route('login');
      }

      return $next($request);

    }
}
