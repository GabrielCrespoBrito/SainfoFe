<?php

namespace App\Http\Middleware;

use Closure;
use App\Empresa;

class EmpresaIsActiveMiddleware
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

    if (! Empresa::find($request->empresa)->isActive()) {
      auth()->logout();
      noti()->error('La Empresa no  se encuentra activa');
      return redirect()->route('login');
    }

    return $next($request);
  }
}
