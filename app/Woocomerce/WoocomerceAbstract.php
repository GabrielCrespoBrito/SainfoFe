<?php

namespace App\Woocomerce;

use Automattic\WooCommerce\Client;

abstract class  WoocomerceAbstract
{
  public $success = true;
  public $error = true;
  public $data = null;
  public $client = null;

  public function __construct()
  {
    $this->callClient();
  }

  public function callClient()
  {
    $credentials = (new WoocomerceCredentials)->getCredentials();

    try {
      $this->client = new Client(
        $credentials->url,
        $credentials->client,
        $credentials->client_key,
        ['version' => 'wc/v3']
      );
    } catch (\Throwable $th) {
      $this->error = $th->getMessage();
      $this->success = false;
    }
  }

  public function getUrlCall($method, $parameters = [])
  {
    $url = $method;

    if ($parameters) {
      foreach ($parameters as $key => $value) {
        $url .= sprintf('?%s=%s', $key, $value);
      }
    }

    return $url;
  }

  public function processResult($result)
  {
    return ProcessResulterResolver::get($result, $this, $this->client);
  }

  public function callAllApi($method, $parameters = [])
  {
    try {
      $result = $this->client->get($this->getUrlCall($method, $parameters));
      $processResult = $this->processResult($result);
      $processResult->all();
      $this->error = $processResult->getError();
      $this->success = $processResult->getSuccess();
      $this->data = $processResult->getData();
    } catch (\Throwable $th) {
      $this->success = false;
      $this->error = $th->getMessage();
    }

    return $this;
  }


  public function callGetApi($method, $parameters = [])
  {
    try {
      $result = $this->client->get($this->getUrlCall($method, $parameters));
      $processResult = $this->processResult($result);
      $processResult->get();
      $this->error = $processResult->getError();
      $this->success = $processResult->getSuccess();
      $this->data = $processResult->getData();
    } catch (\Throwable $th) {
      $this->success = false;
      $this->error = $th->getMessage();
    }

    return $this;
  }


  public function callUpdateApi($method, $data = [])
  {
    try {
      $result = $this->client->put($this->getUrlCall($method), $data);
      $processResult = $this->processResult($result);
      $processResult->update();
      $this->error = $processResult->getError();
      $this->success = $processResult->getSuccess();
      $this->data = $processResult->getData();
    } catch (\Throwable $th) {
      $this->success = false;
      $this->error = $th->getMessage();
    }
    return $this;
  }


  public function callDeleteApi($method, $data = [])
  {
    try {
      $result = $this->client->delete($this->getUrlCall($method), $data);
      $processResult = $this->processResult($result);
      $processResult->delete();
      $this->error = $processResult->getError();
      $this->success = $processResult->getSuccess();
      $this->data = $processResult->getData();
    } catch (\Throwable $th) {
      $this->success = false;
      $this->error = $th->getMessage();
    }
    return $this;
  }
  

  


}
