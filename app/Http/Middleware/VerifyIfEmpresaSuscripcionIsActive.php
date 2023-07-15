<?php

namespace App\Http\Middleware;

use Closure;

class VerifyIfEmpresaSuscripcionIsActive
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
    // if (config('app.amb') === "web") {
    if (get_empresa()->isSuscripcionVencida()) {
      notificacion('Suscripción vencida', 'Su suscripción esta vencida, por favor Renuevela o Cambiela si quiere seguir disfrutrando del servicio', 'error');;
      return redirect()->route('suscripcion.planes.index');
    }
    // }


    return $next($request);
  }
}
