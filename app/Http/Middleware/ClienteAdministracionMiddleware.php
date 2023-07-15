<?php

namespace App\Http\Middleware;

use Closure;

class ClienteAdministracionMiddleware
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
		$user = auth()->user();
		if( is_null($user) ){
			if(session()->has('PCCodi')){
				return $next($request);				
			}
			else {
				return redirect()->route('login');
			}
		}
		else {
			if( $user->isAdmin() || $user->isContador() ){
				return $next($request);
			}
		}

		return redirect()->route('login');
	}
}
