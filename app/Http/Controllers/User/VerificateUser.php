<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Support\Facades\DB;
use App\Jobs\Empresa\GetSeriesDefecto;
use App\Notifications\EmpresaRegister;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\Empresa\CreateEmpresaForUser;
use App\Http\Requests\User\SavePhoneRequest;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\User\VerifyCodeRequest;
use App\Http\Requests\User\StoreClaveSolRequest;
use App\Http\Requests\User\SaveEmpresaInformacionRequest;

trait VerificateUser
{
  public function verificarUser()
  {
    $hasNumberSave = auth()->user()->hasPhoneNumber();
    $number = auth()->user()->phoneNumber();
    return view('auth.verificar', [
      'hasNumberSave' => $hasNumberSave,
      'number' => $number,
    ]);
  }

  public function storePhone(SavePhoneRequest $request)
  {
    $user = auth()->user();
    $user->savePhone($request->phone);
    $user->generateAndSaveVerificactionCode();
    $user->sendActivationCode();
    $user->saveIntentoEnvio();
    return response()->json(['message' => "Codigo aceptado ", 'phone' => $request->phone, 'success' => true]);
  }

  public function verifyCode(VerifyCodeRequest $request)
  {
    auth()->user()->setVerificate();
    return response()->json(['message' => 'Codigo de verificación correcto', 'success' => true, 'route' => route('usuario.verificar_empresa')]);
  }


  public function showFormSol()
  {
    $dataUser = json_decode(auth()->user()->getVerificationCode());

    $data = [
      'ruc' => "555",
      'razon_social' => "asdad",
      'nombre_comercial' => "nombre",
      'direccion' => "direccion",
      'email' => "asdasd@asdasd.com",
    ];

    return view('auth.registra_empresa', $data);


    if (auth()->user()->hasSolData()) {
      json_decode(auth()->user()->getVerificationCode());
      return view('auth.registra_empresa', $data);
    }

    return view('auth.verificar_sol');
  }

  public function storeSolEmpresa(StoreClaveSolRequest $request)
  {
    auth()->user()->saveDataSol($request);
    notificacion('Datos guardados', 'Clave sol registrada correctamente');
    return response()->json(['message' => 'Clave sol registrada', 'success' => true, 'route' => route('usuario.verificar_empresa')]);
  }


  public function saveEmpresaInformation(SaveEmpresaInformacionRequest $request)
  {
    $user = auth()->user();
    $empresa = null;

    try {
      $creatorData = new CreateEmpresaForUser($user, $request);
      $creatorData->handle();
      global $empresa;
      $empresa = $creatorData->empresa;
      //  Crear bds 
      Artisan::call('tenant:create', ['empcodi' => $empresa->empcodi]);
      // Asociar el usuario con la empresa
      $demoSeries =  (new GetSeriesDefecto())->handle();
      $user->asociateToEmpresa($empresa->empcodi, true, $demoSeries);
      User::first()->asociateToEmpresa($empresa->empcodi, true, $demoSeries);
      $user->setAllPermissions();
      $empresa->createPlanes(true);
      empresa_bd_tenant($empresa->empcodi);
      $empresa->syncMedioPagos();
      $empresa->subirCertificadoPrueba();
      Notification::send(User::first(), new EmpresaRegister($empresa));
    } catch (\Throwable $th) {
      optional($empresa)->deleteForceDatabase();
      optional($empresa)->deleteInfoInDatabasePrincipal();
      throw $th;
    }

    noti()->success('Registro realizado exitosamente', 'El registro de la empresa se ha efectuado satisfactoriamente, por favor ingrese sus datos en el login');
    auth()->logout();
    return response()->json(['success' => true, 'route' => route('login')]);
  }

  public function reenviarCodigo()
  {
    $user = auth()->user();
    if ($user->hasLimitCodeVerificationCodeExcede()) {
      $routeInfoContacto = route('user.contacto');
      noti()->error('Limite de envios', "Ha alcanzando el limite de envios del codigo de verificacion. Por favor comuniquese con nosotros, por algunos de nuestros <a target='_blank' href='{$routeInfoContacto}'> Medios de contacto </a>");
    } else {
      $user->generateAndSaveVerificactionCode();
      $user->sendActivationCode();
      $user->saveIntentoEnvio();
      noti()->success('Codigo reenviado', "El codigo de verificación ha sido reenviado a su número de télefono");
    }

    return redirect()->back();
  }
}
