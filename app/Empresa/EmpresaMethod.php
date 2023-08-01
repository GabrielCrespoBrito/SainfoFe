<?php

namespace App\Empresa;

use App\Cargo;
use App\Empresa;
use Carbon\Carbon;
use App\Jobs\Empresa\TokenHandler;
use Illuminate\Support\Facades\Log;
use App\Jobs\Empresa\GetOrGenerateGuiaTokenApi;
use App\Jobs\Empresa\CambiarAplicacionIgvProductos;
use App\Jobs\Empresa\SaveWeb;

trait EmpresaMethod
{
  public function getOrGenerateGuiaTokenApi()
  {
    return (new GetOrGenerateGuiaTokenApi($this))
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


  public static function getClienteGuiaKey()
  {
    return $this->FE_CLIENT_KEY;
  }

  public function getTokenData(): TokenHandler
  {
    return new TokenHandler(json_decode(get_option('Logfond')));
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



}
