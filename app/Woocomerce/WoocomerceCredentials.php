<?php

namespace App\Woocomerce;

class WoocomerceCredentials
{
  protected $credentials;
  protected $hasCredentials;

  public function __construct()
  {
    $empresa = get_empresa();

    $url = $empresa->getDataAditional('woocomerce_api_url');

    if( ! $url ){
      $this->setHasCredentials(false);
      return;
    }

    $credentials = (object) [
      'url' => $url,
      'client' => $empresa->getDataAditional('woocomerce_client'),
      'client_key' => $empresa->getDataAditional('woocomerce_client_key'),
    ];

    $this->setCredentials($credentials);
  }

  public function setHasCredentials($hasCredentials)
  {
    $this->hasCredentials = $hasCredentials;
  }

  public function hasCredentials()
  {
    return $this->hasCredentials;
  }

  public function setCredentials($credentials)
  {
    $this->credentials = $credentials;
  }

  public function getCredentials()
  {
    return $this->credentials;
  }
}
