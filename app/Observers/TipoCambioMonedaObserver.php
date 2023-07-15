<?php

namespace App\Observers;

use App\TipoCambioMoneda;

class TipoCambioMonedaObserver
{
    public function creating( TipoCambioMoneda $tc )
    {
        $tc->TipCodi = $tc->getCodi();
        $tc->empcodi = empcodi();
    }
}
