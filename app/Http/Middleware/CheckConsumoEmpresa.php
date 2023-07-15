<?php

namespace App\Http\Middleware;

use App\Models\Suscripcion\Caracteristica;
use Closure;

class CheckConsumoEmpresa
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $caracteristica)
	{
		return $next($request);

		if (get_empresa()->consumoMaximo($caracteristica)) {
			
			$nombreCaracteristica = Caracteristica::getNombre($caracteristica);
			$message = "Se ha alcanzando el maximo de {$nombreCaracteristica} que puede crear en la empresa";

			if($request->ajax()){
				return response()->json(['message' => $message ], 400);
			}

			else {
				notificacion( "Limite alcanzado", $message, "error" );
				return redirect()->back();
			}
		}

		return $next($request);
	}
}
