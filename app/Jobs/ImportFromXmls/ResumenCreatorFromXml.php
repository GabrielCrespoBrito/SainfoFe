<?php

namespace App\Jobs\ImportFromXmls;

use App\Util\ResultTrait;

class ResumenCreatorFromXml extends CreatorAbstract
{
  public function generateData()
  {
    $this->data = [];
  }

  public function saveDataModel()
  {
    return $this->data;
  }
}


