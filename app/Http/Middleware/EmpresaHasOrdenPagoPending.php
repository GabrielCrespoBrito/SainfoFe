<?php

namespace App\Http\Middleware;

use Closure;

class EmpresaHasOrdenPagoPending
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
        // return $next($request);

        if( get_empresa()->ordenes_pago->where('estatus' , 'pendiente')->count()){
            notificacion('Orden de Pago Pendiente' , 'La empresa ya tiene una orden de pago pendiente para renovar/cambiar de plan','error');
            return redirect()->route('suscripcion.ordenes.index');
        }


        return $next($request);
    }
}
