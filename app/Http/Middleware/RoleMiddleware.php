<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, ... $roles )
	{

		$autorizacion = false;

		foreach ( $roles as $rol ) {            
			if( auth()->user()->hasRole( $rol ) ){
				$autorizacion = true;
				break;
			}
		}

		if( ! $autorizacion ){
			return redirect('/home');            
		}

		return $next($request);
	}
}
