<?php

namespace App\Http\Middleware;

use Closure;

class ElegirPeriodoMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next )
	{
		if( ! session()->has('empresa') ){
			return redirect()->route('elegir_empresa');
		}

		return $next($request);
	}
}
