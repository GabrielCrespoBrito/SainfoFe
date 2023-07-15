<?php

namespace App\Providers;

use App\Caja;
use App\Venta;
use App\Compra;
use App\Policies\CajaPolicy;
use App\Policies\CompraPolicy;
use App\Policies\VentasPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
		/**
		 * The policy mappings for the application.
		 *
		 * @var array
		 */
		protected $policies = [
			Caja::class   => CajaPolicy::class,
			Venta::class  => VentasPolicy::class,
			Compra::class => CompraPolicy::class,
		];

		/**
		 * Register any authentication / authorization services.
		 *
		 * @return void
		 */
		public function boot()
		{
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
			
				$this->registerPolicies();

        Gate::before(function ($user, $ability) {
          if ($user->isAdmin()) {
            return true;
          }
				});


				Gate::define('IsAdmin', function ($user, $caja) {
					return $user->isAdmin();
				});


				Gate::define('manipular-caja', function ($user, $caja) {
					return true;
				});

				Gate::define('baja-documento', function ($user, $documento) {
          return Caja::hasAperturada();
				});

				Gate::define('show-documento', function ($user, $documento){
					$empcodi = $documento->empcodi ?? $documento->EmpCodi;					
					return $user->empresas->where('empcodi' , $empcodi)->count();
				});

        Gate::define('anular-boleta', function ($user, $documento) {
          return $documento->VtaCDR ;
        });

        Gate::define('anular-documento', function ($user, $documento) {
					return $documento->VtaCDR == "1" && $documento->VtaEsta !== "A";
        });

        Gate::define('enviar-email-documento-cliente', function ($user, $documento) {
          return $documento->cliente->PCMail  =! "";
				});
				
        Gate::define('trabajar-con-documento' ,  function($user,$documento){
        	return $user->empresas->where('EmpCodi' , $documento->EmpCodi )->count();
				});
				
        Gate::define('delete-unidad' ,  function($user,$unidad){
        	return substr($unidad->Unicodi,-2) !== "01";           	
				});

        Gate::define('crear-producto' ,  function($user,$empresa){
					return  
					$empresa->marcas->count() && 
					$empresa->familias->count() &&
					$empresa->grupos->count();
				});

				Gate::define('manipular-info' , function( $user , $model ){
					
					$table = $model->getTable();
					$user_empresas = $user->empresas;

					if( \Schema::hasColumn( $table, 'EmpCodi') ){
						return $user_empresas->where('empcodi', $model->EmpCodi )->count();
					}

					else if( Schema::hasColumn( $table, 'empcodi' ) ){
						return $user_empresas->where('empcodi', $model->empcodi )->count();
					}

				});

		}
}
