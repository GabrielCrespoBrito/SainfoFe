<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use GuzzleHttp\Client;
use App\Util\ResultTrait;
use Exception;
use GuzzleHttp\Exception\ClientException;

class GetOrGenerateGuiaTokenApi
{
  use ResultTrait;

  protected $empresa;
  protected $clientId;
  protected $clientKey;
  protected $tokenHandler;
  protected $settingsApi;

  public function __construct(Empresa $empresa)
  {
    $this->empresa = $empresa;
    $this->clientId = $empresa->getClienteGuiaId();
    $this->clientKey = $empresa->getClienteGuiaKey();
    $this->tokenHandler = $empresa->getTokenData();
    $this->settingsApi = settings_api();
  }

  public function validCredentials()
  {
    if (is_null($this->clientId) || is_null($this->clientKey)) {
      return $this->setError('Las Credenciales de ClientId y ClienteKey no se encontrarón, Establezca en Parametros Empresa > Sunat');
    }
    
    return true;
  }

  public function validToken()
  {
    if (!$this->tokenHandler->exists()) {
      return false;
    }

    if ($this->tokenHandler->isExpirit()) {
      return false;
    }   

    return true;
  }

  public function generateToken()
  {
    $client = new Client();
    $options = [
      'form_params' => [
        'grant_type' => 'password',
        'scope' => 'https://api-cpe.sunat.gob.pe/',
        'client_id' =>  $this->clientId,
        'client_secret' => $this->clientKey,
        'username' => $this->empresa->userSolComplete(),
        'password' => $this->empresa->claveSol()
      ]
    ];
    $url =  sprintf("https://api-seguridad.sunat.gob.pe/v1/clientessol/%s/oauth2/token/", $this->clientId);
    try {
      $res = $client->post($url, $options);
      $content = json_decode($res->getBody()->getContents());
      $token = $this->empresa->saveTokenApi( $content );
      $this->setSuccess(['token' => $token ]);
    } catch (ClientException $th) {
      $infoError = json_decode($th->getResponse()->getBody()->getContents());
      if (property_exists($infoError, 'error_description')) {
        $cod = $infoError->error;
        $msg = $infoError->error_description;
      } else {
        $cod = $infoError->cod;
        $msg = $infoError->msg;
      }
      return $this->setError( sprintf('Cod: %s | %s', $cod, $msg));
    }
    catch( Exception $th ){
      return $this->setError($th->getMessage());
    }
  }

  public function handle()
  {
    if (!$this->validCredentials()) {
      return $this;
    }


    if ($this->validToken()) {
      $this->setSuccess(['token' => $this->tokenHandler->getToken()]);
      return $this;
    }

    $this->generateToken();
    return $this;
  }
}
