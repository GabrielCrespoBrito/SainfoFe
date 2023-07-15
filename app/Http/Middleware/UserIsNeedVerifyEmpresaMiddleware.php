<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class UserIsNeedVerifyEmpresaMiddleware
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
    if (auth()->user()->empresas->count()) {
      auth()->logout();
      noti()->error('Error', 'Usuario ya tiene asociada alguna empresa');
      return redirect()->route('home');
    }

    if (!config('auth.register_user')) {
      auth()->logout();
      noti()->error('Error', 'El registro de usuario esta desactivado');
      return redirect()->route('login');
    }

    if (auth()->user()->isVerificated()) {
      noti()->error('Error', 'Acceso denegado');
      return redirect()->route('login');
    }


    return $next($request);
  }
}
