<?php

namespace App\Models\Produccion;

use App\Jobs\CreateMovs;
use App\Jobs\SetCostos;
use App\Presenter\ProduccionPresenter;
use App\Producto;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Produccion extends Model
{
  use UsesTenantConnection;

  protected $primaryKey = "manId";
  protected $descripcionKey = "manId";
  protected $keyType = "string";
  protected $table = "produccion_manual";
  public $fillable = [
    'manEsta',
    'manFechCulm'
  ];

  const ESTADO_ANULAD = "ANUL.";
  const ESTADO_CULMINADO = "CULM.";
  const ESTADO_ASIGNADO = "ASIG.";
  const ESTADO_PRODUCCION = "PROD.";

  const ESTADO_ANULAD_NOMBRE = "Anulado.";
  const ESTADO_CULMINADO_NOMBRE = "Culminado";
  const ESTADO_ASIGNADO_NOMBRE = "Asignado";
  const ESTADO_PRODUCCION_NOMBRE = "Producción";

  const CREATED_AT = "USER_FCREA";
  const UPDATED_AT = "USER_FMODI";

  public function presenter()
  {
    return new ProduccionPresenter($this);
  }

  public function isEstadoAsignado()
  {
    return $this->manEsta == self::ESTADO_ASIGNADO;
  }

  public function isEstadoAnulado()
  {
    return $this->manEsta == self::ESTADO_ANULAD;
  }

  public function isEstadoCulminado()
  {
    return $this->manEsta == self::ESTADO_CULMINADO;
  }

  public static function getEstados()
  {
    return [
      self::ESTADO_ANULAD => self::ESTADO_ANULAD_NOMBRE,
      self::ESTADO_CULMINADO => self::ESTADO_CULMINADO_NOMBRE,
      self::ESTADO_ASIGNADO => self::ESTADO_ASIGNADO_NOMBRE,
      self::ESTADO_PRODUCCION => self::ESTADO_PRODUCCION_NOMBRE,
    ];
  }

  public function items()
  {
    return $this->hasMany(ProduccionDetalle::class, 'manId', 'manId');
  }

  public function  updateEstado($estado)
  {
    $data = ['manEsta' => $estado];

    if( $estado == self::ESTADO_CULMINADO ){
      $data['manFechCulm'] = datePeru('Y-m-d H:i:s');
    }

    $this->update($data);
  }


  public function producto()
  {
    return $this->belongsTo(Producto::class, 'manCodi', 'ProCodi');
  }

  public function getNextId()
  {
    $lastProduccion = self::orderByDesc('USER_FCREA')->first();

    if ($lastProduccion) {
      $id = (int) substr($lastProduccion->manId, 2);
      $correlativo = agregar_ceros($id, 6);
      return sprintf('PM%s', $correlativo);
    } else {
      return 'PM000001';
    }
  }

  public function getEstadosToEdit()
  {
    if (!$this->canChangeEstado()) {
      return [];
    }

    if ($this->isEstadoAsignado()) {
      return [
        self::ESTADO_PRODUCCION => self::ESTADO_PRODUCCION_NOMBRE,
        self::ESTADO_ANULAD => self::ESTADO_ANULAD_NOMBRE,
      ];
    }

    return [
      self::ESTADO_CULMINADO => self::ESTADO_CULMINADO_NOMBRE,
      self::ESTADO_ANULAD => self::ESTADO_ANULAD_NOMBRE,
    ];
  }

  public function canChangeEstado()
  {
    return $this->manEsta == self::ESTADO_ASIGNADO || $this->manEsta == self::ESTADO_PRODUCCION;
  }

  public function createGuias()
  {
    return (new CreateMovs($this))->handle();
  }


  public function setCostos()
  {
    return (new SetCostos($this))->handle();
  }

  public function getSerieGuia($ingreso)
  {
    return 'PM0' . ($ingreso ? 'I' : 'S');
  }

  public function getNumeroGuia()
  {
    return substr($this->manId, 2);
  }
}
