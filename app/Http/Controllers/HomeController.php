<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Models\Cierre;
use Illuminate\Http\Request;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Contracts\Tenant;
use Automattic\WooCommerce\Client;
use App\Helpers\NotificacionHelper;
use Illuminate\Support\Facades\Auth;
use App\Jobs\System\CheckIfMonthExists;
use Illuminate\Support\Facades\Storage;
use Codexshaper\WooCommerce\Models\Order;
use App\Http\Controllers\Reportes\Reporte;
use App\Http\Requests\EmpresaSeleccionadaRequest;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('basehost.enforce', ['only' => 'ElegirEmpresa']);
  }

  /**
   * Metodo para redirigir al usuario despues de logearse
   *
   * @return void
   */
  public function redirectTo()
  {
    $user = auth()->user();

    if ($user->isVerificated()) {
      if ($user->hasEmpresa()) {
        return redirect()->route('elegir_empresa');
      } else {
        return redirect()->route('usuario.verificar_empresa');
      }
    } else {
      return redirect()->route('usuario.verificar');
    }
  }

  public function index()
  {
    $user  = auth()->user();
    if ($user->isContador()) {
      $routeData = route('reportes.ventas_mensual_getdata');
      $routeDate = route('reportes.consult_date');
      return view('reportes.ventas_mensual.form_new_contador', compact('routeData', 'routeDate'));
    } else {
      $empresa = Empresa::find(empcodi());
      (new CheckIfMonthExists)->handle();
      if ($empresa->noSeHaGuardadoInformacionPorDefecto()) {
        try {
          $empresa->saveInformacionDefecto(true);
          $empresa->guardarEstadoSeHaGuardadoInformacionPorDefecto();
          $empresa->cleanCache();
        } catch (\Throwable $th) {
          $empresa->DeleteAllInfoUser();
          logger('@ERROR ERROR REGISTRANDO INFORMACIÒN POR DEFECTO DE EMPRESA');
          noti()->error('No se pudo crear su informaciòn por defecto para acceder, intente de nuevo');
          Auth::logout();
          return back();
        }
      }

      // dd(1);
      // exit();
      // storage_path();
      // Storage::disk('ftp')->put('avatars/1.txt', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum' );

      return view('dashboard', [ 'sidebar' => false ]);
    }
  }

  public function ElegirEmpresa(Request $request)
  {
    $user = auth()->user();
    $empresas = $user->empresas->load('empresa.periodos');
    $empresaRucSelected = null;
    // Tenant::first()
    // Obtener el subdominio


    $host = $request->getHost();
    $subdomain = explode('.', $host);  // Asumiendo que el subdominio es el primer segmento del host
    // $subdomain = explode('.', $host)[0];  // Asumiendo que el subdominio es el primer segmento del host

    if(count($subdomain) > 1){
      $empresaRucSelected = $subdomain = $subdomain[0];
    }


    if ($user->isAdmin()) {
      $empresas = $empresas->filter(function ($empresa) {
        return optional($empresa->empresa)->isActive();
      });
    }

    return view('elegir_empresa', [
      'empresas' => $empresas,
      'empresaRucSelected' => $empresaRucSelected
    ]);
  }

  public function EmpresaSeleccionada(EmpresaSeleccionadaRequest $request)
  {
    // Really I Just say
    $empresa = Empresa::find($request->empresa);
    $ruc = $empresa->EmpLin1;
    $fqdn = $ruc . '.' . config('app.url_base');
    $hostExists = Hostname::where('fqdn', $fqdn)->exists();
    $puerto = env('SERVER_PORT');
    $port = $request->server('SERVER_PORT') == $puerto ? ":{$puerto}" : '';

    if ($hostExists) {
      session()->put('empresa', $empresa->empcodi);
      session()->put('empresa_ruc', $ruc);
      session()->put('empresa_nombre', $empresa->EmpNomb);
      session()->put('periodo', $request->periodo);
      session()->flash('elegida_empresa', true);
      // http://
      // $url = sprintf( '%s/%s%s/home',)($request->secure() ? 'https://' : 'http://') .
      $url = sprintf('%s/%s%s/home', ($request->secure() ? 'https://' : 'http://'), $fqdn, $port);
      return redirect($url);
    } else {
      Auth::logout();
      noti()->error('Esta empresa no tiene una base de datos asociada');
      return redirect()->route('login');
    }
  }
}
