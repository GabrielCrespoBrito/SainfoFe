<?php

namespace App\Util\Sire;

use Exception;
use App\Empresa;
use GuzzleHttp\Client;
use App\Util\ResultTrait;
use GuzzleHttp\Exception\ClientException;

abstract class SireApi  
{
  use ResultTrait;

  public $empresa;
  public $parameters;
  public $token;
  public $isPost;
  public $isJsonResponse = true;

  public function __construct( Empresa $empresa, bool $isPost, array $parameters = [])
  {
    $this->empresa = $empresa;
    $this->parameters = (object) $parameters;
    $this->isPost = $isPost;
  }


  public function setIsJsonResponse( $res = true)
  {
    $this->isJsonResponse = $res;

    return $this;
  }

  public function generateToken()
  {
    $res = $this->empresa->getOrGenerateSireTokenApi();

    if ($res->success) {
      $this->token = $res->data['token'];
      return true;
    }

    return $this->setError($res->data);
  }

  public function getOptionAuthorization()
  {
    return [
      'headers' => [
        'Authorization' => sprintf("Bearer %s", $this->token)
      ]
    ];
  }

  public function handle()
  {

    if (!$this->generateToken()) {
      return $this;
    }

    $this->callApi();

    return $this;
  }

  public function processErrorClient( ClientException $th )
  {
    
    $infoError = json_decode($th->getResponse()->getBody()->getContents());
    $errors_message = "";
    if (property_exists($infoError, 'error_description')) {
      $cod = $infoError->error;
      $msg = $infoError->error_description;
    } else {
      $cod = $infoError->cod;
      $msg = $infoError->msg;
      $errors = optional($infoError)->errors;
      if(is_array($errors)){
        foreach( $errors as $error ){
          $errors_message .=  sprintf('[Cod: %s - Mensj: %s]', $error->cod, $error->msg ); 
        }
      }
    }
    $this->setError(sprintf('Cod: %s | %s %s', $cod, $msg, $errors_message));
  }

  public function getUrl()
  {
    return "";
  }

  public function getOption()
  {
    return $this->getOptionAuthorization();
  }

  public function callApi()
  {
    $client = new Client();
    
    // _dd($this->getUrl(), $this->getOption());
    // exit();
    
    try {
      $res = $this->isPost ? 
      $client->post($this->getUrl(), $this->getOption()) : 
      $client->get($this->getUrl(), $this->getOption());

      // _dd($res->getBody()->getContents());
      // exit();
      $content = $this->isJsonResponse ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents();
      $this->setSuccess($content);
    } catch (ClientException $th) {
      $this->processErrorClient($th);
    } catch (Exception $th) {
      $this->setError($th->getMessage());
    }
  }

}