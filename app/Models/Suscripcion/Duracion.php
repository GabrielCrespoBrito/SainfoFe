<?php

namespace App\Models\Suscripcion;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Duracion extends Model
{
    use UsesSystemConnection;

    protected $table = "suscripcion_system_duraciones";

    const DURACION_MENSUAL = "meses";
    const DURACION_DIARIO = "dias";

    public function isMensual()
    {
        return $this->tipo_duracion === self::DURACION_MENSUAL;
    }

    public function isDiario()
    {
        return $this->tipo_duracion === self::DURACION_DIARIO;
    }

    public function getNombre()
    {
        return $this->duracion . ' ' . ($this->isDiario() ? 'Días' : 'Meses');
    }

}
