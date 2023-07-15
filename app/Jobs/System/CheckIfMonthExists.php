<?php

namespace App\Jobs\System;

use Illuminate\Support\Facades\Artisan;

/**
 * Verificar si estamos en el primer dia del mes, si es asi, guardar en la BD el nuevo mes si no se ha creado 
 */

class CheckIfMonthExists 
{
    public function handle()
    {
      Artisan::call('system_task:crear_mes');
    }
}
