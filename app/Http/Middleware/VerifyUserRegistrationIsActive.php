<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class VerifyUserRegistrationIsActive
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
    if (!config('auth.register_user')) {

      $message  = sprintf("El registros de usuarios nuevos esta desactivado en estos momentos, si desea crear una cuenta por favor comuniquese con nosotros en <a href='%s' _target='blank'>Pagina de Contacto</a> ", config('app.url_contacto'));
      noti()->error('El registro inactivo', $message);

      if (auth()->guest()) {
        return redirect()->route('login');
      }

      if(auth()->user()->isVerificated()){
        return redirect()->route('home');
      }

      auth()->logout();
      return redirect()->route('login');

    }

    return $next($request);
  }
}