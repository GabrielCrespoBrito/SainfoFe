<?php

namespace App\Http\Middleware;

use Closure;
use App\Empresa;

class ForceUrlTenancy
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
        
        
        if( strpos($fqdn,'.')  !== false ){
            dd( "todo perfect,", $fqdn, strpos($fqdn,'.'));
            return $next($request);
        }
        
        else {
            
            $empresa = Empresa::find( session('empresa')  );
            $ruc = $empresa->EmpLin1;
            $fqdn = $ruc . '.' . config('app.url_base');
            $puerto = env('SERVER_PORT');
            $port = $request->server('SERVER_PORT') == $puerto ? ":{$puerto}" : '';
            
            $url = ($request->secure() ? 'https://' : 'http://') . 
            $fqdn . 
            $port . 
            '/home';
            
            return redirect($url);
        }
          
	}

}
