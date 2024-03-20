<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Models\Cierre;
use Illuminate\Http\Request;
use Hyn\Tenancy\Models\Hostname;
use Automattic\WooCommerce\Client;
use App\Helpers\NotificacionHelper;
use Illuminate\Support\Facades\Auth;
use App\Jobs\System\CheckIfMonthExists;
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
      return view('contador.index');
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

      return view('dashboard', [
        // 'data' => $data,
        'sidebar' => false,
        // 'data_mes' => $data_mes,
        // 'data_grafica' => $data_grafica,
      ]);
    }
  }

  public function ElegirEmpresa(Request $request)
  {
    $user = auth()->user();
    $empresas = $user->empresas->load('empresa.periodos');

    if ($user->isAdmin()) {
      $empresas = $empresas->filter(function ($empresa) {
        return optional($empresa->empresa)->isActive();
      });
    }

    return view('elegir_empresa', [
      'empresas' => $empresas
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
      $url = ($request->secure() ? 'https://' : 'http://') .
      // 208576552.localhost
      $fqdn .
      // :8001 | :8000 |  
      $port .
      // home
      '/home';
      
      // dd( $url );
      // exit();
      return redirect($url);
    } else {
      Auth::logout();
      noti()->error('Esta empresa no tiene una base de datos asociada');
      return redirect()->route('login');
    }
  }
}
