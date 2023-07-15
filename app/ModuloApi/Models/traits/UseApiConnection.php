<?php

namespace App\ModuloApi\Models\traits;

trait UseApiConnection 
{
  public function getConnectionName()
  {
    return 'mysql_api';
  }

}