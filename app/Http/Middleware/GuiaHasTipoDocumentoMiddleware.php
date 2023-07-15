<?php

namespace App\Http\Middleware;

use App\GuiaSalida;
use App\SerieDocumento;
use Closure;

class GuiaHasTipoDocumentoMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next, $tipoDoc = GuiaSalida::TIPO_GUIA_REMISION)
  {
    # Tipo de documento de las guias
    $tdGuias  = SerieDocumento::ultimaSerie(true, $tipoDoc);
    
    if ($tdGuias->count() == 0) {
      $nombre =  GuiaSalida::getNombreRead($tipoDoc);
      notificacion('Acceso denegado', sprintf('No tiene serie para %s', $nombre) , 'warning');
      return back();
    }

    return $next($request);
  }
}
