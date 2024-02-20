<?php

namespace App\Jobs\Admin;

use App\Empresa;
use Hyn\Tenancy\Database\Connection;

class ActiveEmpresaTenant
{
  public function __construct(Empresa $empresa)
  {
    $this->empresa = $empresa;
  }

  public function handle()
  {
    $connection = app(Connection::class);
    $connection->set($this->empresa->getWebsite());
    session()->put('empresa', $this->empresa->empcodi);
  }
}