<?php

namespace App\Util\Sire;

use Exception;
use App\Empresa;
use GuzzleHttp\Client;
use App\Util\ResultTrait;
use GuzzleHttp\Exception\ClientException;


class GenerateTokenSire 
{
  use ResultTrait;

  protected $tokenHandler;
  protected $clientId;
  protected $clientKey;
  protected $empresa;

  public function __construct( Empresa $empresa )
  {
    $this->empresa = $empresa;
    $this->tokenHandler = $empresa->getTokenData('LogArti');

    // $this->clientId = $empresa->FE_CLIENT_ID;
    // $this->clientKey = $empresa->FE_CLIENT_KEY;
    $this->clientId = "3d0cbe02-7703-44bb-8e6b-f7e4ff496f41";
    $this->clientKey = "I9vAcilQXHB6QX8PiJ7Wng==";
  }

  const URL = "https://api-seguridad.sunat.gob.pe/v1/clientessol/%s/oauth2/token";

  public function getUrl()
  {
    return sprintf( self::URL, $this->clientId); 
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


  public function getOptions()
  {
    return [
      'form_params' => [
        'grant_type' => 'password',
        'scope' => 'https://api-cpe.sunat.gob.pe',
        'client_id' =>  $this->clientId,
        'client_secret' => $this->clientKey,
        // 'username' => $this->empresa->userSolComplete(),
        // 'password' => $this->empresa->claveSol()
        'username' => "20545613345GENARAZI",
        'password' => "arco2054"
      ]
    ];
  }

  public function validCredentials()
  {
    return true;
  }

  public function generateToken()
  {
    $client = new Client();

    try {
      $res = $client->post($this->getUrl(), $this->getOptions());
      $content = json_decode($res->getBody()->getContents());
      $token = $this->empresa->saveTokenApiSire($content);
      $this->setSuccess(['token' => $token]);
    } catch (ClientException $th) {
      $infoError = json_decode($th->getResponse()->getBody()->getContents());
      if (property_exists($infoError, 'error_description')) {
        $cod = $infoError->error;
        $msg = $infoError->error_description;
      } else {
        $cod = $infoError->cod;
        $msg = $infoError->msg;
      }
      return $this->setError(sprintf('Cod: %s | %s', $cod, $msg));
    } catch (Exception $th) {
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
