<?php

namespace App\Http\Middleware;

use Closure;

class VerifiyConfigEmpresa
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $verifyConfigINeedToSave = 1)
	{
		if ($verifyConfigINeedToSave) {

			if (get_empresa()->needConfig()) {
				notificacion("Configuración", "Necesita introducir la configuración para empezar a facturar electronicamente", "info");
				return redirect()->route('empresa.config_final');
			}
		} 
		
		else {

			if (!get_empresa()->needConfig()) {
				notificacion("Configuración", "La configuración de la empresa ya se realizo exitosamente", "error");
				return redirect()->route('home');
			}
		}

		return $next($request);
	}
}
