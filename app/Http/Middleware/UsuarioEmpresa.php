<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Comprobar que el usuario 
 */
class UsuarioEmpresa
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
    // Si no esta verificado el usuario, tiene que verificarse
    if(! auth()->user()->isVerificated()){
      noti()->success('' , 'Por terminar el paso de verificación de su cuenta');
      logger('@ERROR Por terminar el paso de verificación de su cuenta');

      return redirect()->route('verificar');
    }

    // Si no tiene empresas asociadas
    if( ! auth()->user()->empresas->count() ){
      
      // Comprobar si se puede registrar la empresa      
      if( config('auth.register_user')  ){
        noti()->success('Faltan un paso' , 'Tiene que registrar los datos de su empresa');
        logger('@ERROR UserEmpresa Faltan un paso Tiene que registrar los datos de su empresa');
        return redirect()->route('usuario.verificar_empresa');
      }
  
      auth()->logout();
      noti()->info('Su usuario no esta asociado a ninguna empresa, por favor comuniquese con el administrador');
      logger('@ERROR Su usuario no esta asociado a ninguna empresa, por favor comuniquese con el administrador');
      return redirect()->route('login');
    }
    
    return $next($request);
  }
}