<?php

namespace App\Http\Middleware;

use Hyn\Tenancy\Models\Hostname;
use Closure;

class TenantExists
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
		$fqdn = $request->getHost();		

    // _dd( $fqdn );
    // exit();

		if (!$this->tenantExists($fqdn)) {
			auth()->logout();
			abort(404, 'Nope.');
			return;
		}

		if( !$this->tenantValid($fqdn) ){
      		auth()->logout();
			abort(404, 'Cliente errorneo.');
			return;
		}

		return $next($request);
	}

	public function tenantValid($fqdn)
	{
		$tenantRuc = "";

		if( str_contains($fqdn , '.') ){
			$tenantRuc = explode('.', $fqdn)[0];
		}
		
		return session('empresa_ruc') == $tenantRuc;
	}

	private function tenantExists($fqdn)
	{
		return Hostname::where('fqdn', $fqdn)->exists();
	}
}