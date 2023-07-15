<?php

namespace App\Repositories;

use App\Repositories\RepositoryBase;

class SystemStatRepository extends RepositoryBase
{
  public $prefix_key = "SYSTEM_STAT";
  protected $cacheKeys = ['all'];

}


