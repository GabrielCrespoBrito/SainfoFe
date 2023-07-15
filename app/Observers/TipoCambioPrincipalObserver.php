<?php

namespace App\Observers;

use App\TipoCambioPrincipal;

class TipoCambioPrincipalObserver
{
    public function creating( TipoCambioPrincipal $tc )
    {
        $tc->TipCodi = $tc->getCodi();
    }
}
