<?php

namespace App\Jobs\Empresa;

class TokenHandler
{
  public function __construct($token)
  {
    ;
    $this->token = $token;
  }

  public function exists()
  {
    return $this->token != null;
  }

  public function isExpirit()
  {
    return $this->token->expires_date < date('Y-m-d H:i:s');
  }

  public function getToken()
  {
    return $this->token->access_token;
  }
}