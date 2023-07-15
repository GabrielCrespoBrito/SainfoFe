<?php

namespace App\Http\Middleware;

use Closure;
use App\Empresa;

class VentaCreateMiddleware
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
        $empresa = get_empresa();
        $user = auth()->user();
        $empcodi = $empresa->empcodi;

        if(!$empresa->almacenes->count()){
          notificacion('Falta algo' , 'No tiene locales registrados', 'error');
          return redirect('home');            
        }

        if(false){
        // if( get_empresa()->consumoMaximo('comprobantes') ){
            notificacion('Falta algo' , 'Ha llegado al limite de comprobantes que puede emitir en el mes, para seguir emitiendo, tiene que cambiar', 'error');
            return redirect('home');    
        }

        $local = $user->localCurrent()->loccodi;

        if ( $local == null) {
          notificacion('Falta algo', 'El usuario no tiene una asociado un local por defecto', 'error');
          return redirect('home');
        }

        if(!$user->documentos->where('empcodi' , $empcodi )->where('loccodi', $local)->count() ){
            notificacion('Falta algo' , 'El usuario no tiene series para registrar documentos en este local', 'error');
            return redirect('home');            
        }



        return $next($request);
    }
}
