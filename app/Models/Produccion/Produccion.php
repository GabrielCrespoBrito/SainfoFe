<?php

namespace App\Models\Produccion;

use App\Presenter\ProduccionPresenter;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Produccion extends Model
{
  use UsesTenantConnection;

  protected $primaryKey = "manId";
  protected $descripcionKey = "manId";
  protected $keyType = "string";
  protected $table = "produccion_manual";

  const ESTADO_ANULAD = "ANUL.";
  const ESTADO_CULMINADO = "CULM.";
  const ESTADO_ASIGNADO = "ASIG.";
  const ESTADO_PRODUCCION = "PROD.";

  public function presenter()
  {
    return new ProduccionPresenter($this);
  }
}
