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

  protected $clientId;
  protected $clientKey;
  protected $empresa;

  public function __construct( Empresa $empresa )
  {
    $this->empresa = $empresa;
    $this->clientId = $empresa->FE_CLIENT_ID;
    $this->clientKey = $empresa->FE_CLIENT_KEY;
  }


  const URL = "https://api-seguridad.sunat.gob.pe/v1/clientessol/%s/oauth2/token/";

  public function getUrl()
  {
    return sprintf( self::URL, $this->clientId); 
  }

  public function getOptions()
  {
    return [
      'form_params' => [
        'grant_type' => 'password',
        'scope' => 'https://api-sire.sunat.gob.pe',
        'client_id' =>  $this->clientId,
        'client_secret' => $this->clientKey,
        'username' => $this->empresa->userSolComplete(),
        'password' => $this->empresa->claveSol()
      ]
    ];
  }

  public function handle()
  {
    $client = new Client();

    try {
      // dd($this->getUrl(), $this->getOptions());
      // exit();
      $res = $client->post($this->getUrl(), $this->getOptions());
      $content = json_decode($res->getBody()->getContents());
      // $token = $this->empresa->saveTokenApiSire($content);
      $token = 1;
      _dd( "content",  $res, $content );
      exit();

      $this->setSuccess(['token' => $token]);
    } catch (ClientException $th) {
      dd( $th );
      exit();
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
}
