<?php

namespace App\Providers;

use App\User;
use App\Local;
use App\Resumen;
use App\Vendedor;
use App\FormaPago;
use App\TipoCambioMoneda;
use App\TipoCambioPrincipal;
use Illuminate\Support\Carbon;
use App\Observers\LocalObserver;
use App\Observers\ResumenObserver;
use App\Observers\VendedorObserver;
use Illuminate\Support\Facades\URL;
use App\Observers\FormaPagoObserver;
use App\Observers\OrdenPagoObserver;
use App\Repositories\UserRepository;
use App\Models\Suscripcion\OrdenPago;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Observers\TipoCambioMonedaObserver;
use App\Util\ConsultDocument\ConsultDniMigo;
use App\Util\ConsultDocument\ConsultRucMigo;
use App\Repositories\UserRepositoryInterface;
use App\Observers\TipoCambioPrincipalObserver;
use App\Util\ConsultDocument\ConsultDniInterface;
use App\Util\ConsultDocument\ConsultRucInterface;
use App\Util\ConsultTipoCambio\ConsultTipoCambioMigoByDate;
use App\Util\ConsultTipoCambio\ConsultTipoCambioMigoByLatest;
use App\Util\ConsultTipoCambio\ConsultTipoCambioByDateInterface;
use App\Util\ConsultTipoCambio\ConsultTipoCambioByLatestInterface;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    ini_set('max_input_vars', 60000);
    if ( $isProduction = config('app.env') == 'production' ) {
      // URL::forceScheme('https');
    }

    Schema::defaultStringLength(191);
    Carbon::setLocale(config('app.locale'));
    
    // Directivas blade
    Blade::directive('add_libreria', function ($expresion) {
      $expresion =  str_replace("'", '', str_replace('"', '', $expresion));
      $args = explode("|", $expresion);
      $librerias = explode(",", $args[0]);
      $tipo = $args[1];
      return add_libreria($librerias, $tipo);
    });
    
    // Directivas blade
    Blade::directive('add_js', function ($expresion) use($isProduction) {
      $expresion = str_replace("'", '', str_replace('"', '', $expresion));
      $asset = asset_force_https('js/' . $expresion, $isProduction);
      return '<script src="' . $asset . '"></script>';
    });

    Blade::component('components.table', 'table');
    Blade::component('components.assets.add_assets', 'add_assets');
    Blade::component('components.view_data', 'view_data');
    
    TipoCambioMoneda::observe(TipoCambioMonedaObserver::class);
    TipoCambioPrincipal::observe(TipoCambioPrincipalObserver::class);

    Resumen::observe(ResumenObserver::class);
    OrdenPago::observe(OrdenPagoObserver::class);
    Vendedor::observe(VendedorObserver::class);
    FormaPago::observe(FormaPagoObserver::class);
    Local::observe(LocalObserver::class);

    view()->composer(['layouts.admin.partials.header_notificacion'], 
      function ($view) {                
      $view->with('notificaciones_data', User::getAdmin()->dataNotifications() );
    });

    $this->repositories();
  }

  public function repositories()
  {
    # Repositorios    

    $this->app->bind(
      UserRepositoryInterface::class,
      UserRepository::class
    );

    $this->app->bind(
      ConsultDniInterface::class,
      ConsultDniMigo::class
    );

    $this->app->bind(
      ConsultRucInterface::class,
      ConsultRucMigo::class
    );

    // $this->app->bind(
    //   ConsultTipoCambioInterface::class,
    //   ConsultTipoCambioMigo::class
    // );

    $this->app->bind(
      ConsultTipoCambioByDateInterface::class,
      ConsultTipoCambioMigoByDate::class
    );

    $this->app->bind(
      ConsultTipoCambioByLatestInterface::class,
      ConsultTipoCambioMigoByLatest::class
    );
  }

  public function register()
  {
  }

  public function render()
  {
  }
}