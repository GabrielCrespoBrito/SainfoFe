<?php

namespace App\Empresa;

use App\User;
use App\Cargo;
use App\Empresa;
use Carbon\Carbon;
use App\Jobs\Empresa\SaveWeb;
use App\Jobs\Empresa\TokenHandler;
use Illuminate\Support\Facades\Log;
use App\Notifications\SuscripcionVencida;
use App\Notifications\SuscripcionPorVencer;
use App\Notifications\SuscripcionVencidaHoy;
use App\Jobs\Empresa\GetOrGenerateGuiaTokenApi;
use App\Jobs\Empresa\CambiarAplicacionIgvProductos;
use App\Notifications\NotifyAdminUserSuscripcionPorVencer;
use App\Notifications\NotifyAdminUserSuscripcionVencida;
use App\Util\Sire\GenerateTokenSire;

trait EmpresaMethod
{
  public function getOrGenerateGuiaTokenApi()
  {
    return (new GetOrGenerateGuiaTokenApi($this))
      ->handle()
      ->getResult();
  }


  public function getOrGenerateSireTokenApi()
  {
    return (new GenerateTokenSire($this))
      ->handle()
      ->getResult();
  }


  public function getClienteGuiaId()
  {
    return $this->FE_CLIENT_ID;
  }

  public static function saveWeb($data)
  {
    return (new SaveWeb($data))->handle();
  }


  public function getClienteGuiaKey()
  {
    return $this->FE_CLIENT_KEY;
  }

  public function getTokenData($tokenCampo = "Logfond"): TokenHandler
  {
    return new TokenHandler(json_decode(get_option($tokenCampo)));
  }
  
  public function generateTokenApi()
  {
  }
  
  public function isEscritorio()
  {
    return $this->tipo == "escritorio";
  }

  public function hasFechaCert()
  {
    return $this->venc_certificado != null;
  }

  public function  fechaCertVencido()
  {
    $carbon = new Carbon($this->venc_certificado);
    $today = new Carbon();

    return $carbon->isSameDay($today) || $carbon->isBefore($today);
  }

  public function fechaCertPorVencer()
  {
    $carbon = new Carbon($this->venc_certificado);
    $carbon->subDays(config('app.recordatorio_venc_certificado'));
    $today = new Carbon();
    return $today->isAfter($carbon);
  }

  public function getFechaSuscripcion()
  {
    $date = explode( ' ', $this->end_plan );
    return $date[0];
  }

  public function isWeb()
  {
    return !$this->isEscritorio();
  }

  public function saveTokenApi($tokenData)
  {
    $tokenData = (array) $tokenData;
    $tokenData['expires_date'] = Carbon::now()->addSeconds($tokenData['expires_in'])->format('Y-m-d H:i:s');

    $this->opcion->update([
      'Logfond' => json_encode($tokenData)
    ]);

    $this->cleanCache();
    return $tokenData['access_token'];
  }

  public function saveTokenApiSire( $tokenData )
  {
    $tokenData = (array) $tokenData;
    $tokenData['expires_date'] = Carbon::now()->addSeconds($tokenData['expires_in'])->format('Y-m-d H:i:s');

    $this->opcion->update([
      'LogArti' => json_encode($tokenData)
    ]);

    $this->cleanCache();

    return $tokenData['access_token'];
  }


  public function userSolComplete()
  {
    return $this->EmpLin1 . $this->FE_USUNAT;
  }

  public function claveSol()
  {
    return $this->FE_UCLAVE;
  }

  public function changeAplicacionIGV($incluir_igv)
  {
    (new CambiarAplicacionIgvProductos($incluir_igv))->handle();
  }

  public function getTipoCaja()
  {
    return $this->{Empresa::CAMPO_TIPO_CAJA};
  }

  public function isTipoCajaLocal()
  {
    return $this->{Empresa::CAMPO_TIPO_CAJA} == Empresa::TIPO_CAJA_LOCAL;
  }


  public function sendEmailVencSuscripcion()
  {
    $userOwner =  $this->userOwner();
    $user_soporte = User::getUserSoporte();
    $userOwner->notify(new SuscripcionVencida($this, $userOwner ));
    // $user_soporte->notify(new NotifyAdminUserSuscripcionVencida($this, $userOwner));
  }
  
  public function sendEmailPorVencSuscripcion()
  {
    $userOwner =  $this->userOwner();
    $user_soporte = User::getUserSoporte();
    $userOwner->notify(new SuscripcionPorVencer($this));
    $user_soporte->notify(new NotifyAdminUserSuscripcionPorVencer($this, $userOwner));
  }


}
