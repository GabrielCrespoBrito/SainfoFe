<?php

namespace App\Http\Middleware;

use Closure;

class OwnerAccess
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
    if (!auth()->user()->isOwner()) {
      if ($request->isJson()) {
        return response()->json(['message' => 'Acceso denegado, no tiene permisos para acceder a estos recursos'], 400);
      }
      notificacion('Acceso denegado', 'No tiene permisos para acceder a estos recursos', 'error');
      return redirect()->route('home');
    }

    return $next($request);
  }
}
