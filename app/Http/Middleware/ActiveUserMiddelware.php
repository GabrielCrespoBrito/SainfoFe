<?php

namespace App\Http\Middleware;

use Closure;

class ActiveUserMiddelware 
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
      if( auth()->user()->active == 0 ){
        notificacion( "Inactivo" , 'Su usuario se encuentra inactivo' , "error" );
        auth()->logout();
        return redirect()->route('login');
      }
      return $next($request);
    }
}
