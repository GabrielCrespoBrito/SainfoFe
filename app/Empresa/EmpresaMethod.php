<?php

namespace App\Empresa;

use App\Empresa;
use Carbon\Carbon;
use App\Jobs\Empresa\TokenHandler;
use Illuminate\Support\Facades\Log;
use App\Jobs\Empresa\GetOrGenerateGuiaTokenApi;
use App\Jobs\Empresa\CambiarAplicacionIgvProductos;

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

  public function getClienteGuiaKey()
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
