<?php

namespace App\Http\Middleware;

use App\M;
use Closure;

class VerificarUserMiddleware
{
    /**
     * Comprobar que un usuario este o no verificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $comprobar_if_is_verificated = 1 )
    {
      $user_is_verificate = auth()->user()->isVerificated();

      $comprobar_if_is_verificated = (bool) $comprobar_if_is_verificated;  
      
      if( $comprobar_if_is_verificated ){
        if ( ! $user_is_verificate ) {
          auth()->logout();
          noti()->error('Usuario verificado', 'El usuario ya se encuentra verificado');
          return redirect()->route('login');
        }
      }

      else {
        if ($user_is_verificate) {
          auth()->logout();
          noti()->error('Usuario verificado', 'El usuario no se encuentra verificado');
          return redirect()->route('login');
        }
      }




      return $next($request);

    }
}
