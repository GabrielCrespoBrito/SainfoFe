<?php

namespace App\Observers;

use App\Local;

class LocalObserver
{
  public function creating(Local $local)
  {
    $empcodi = $local->empcodi ?? empcodi();
    $id = $local->LocCodi ?? Local::getNextID();
    $numlibre = $local->Numlibre ?? Local::getNumlibre();

    $local->LocCodi = $id;
    $local->empcodi = $empcodi;
    $local->Numlibre = $numlibre;
  }

}
