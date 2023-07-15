<?php

namespace App\Models\TomaInventario;

use App\Local;
use Illuminate\Database\Eloquent\Model;
use App\Presenter\TomaInventarioPresenter;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Repositories\TomaInventarioRepository;
use App\Models\TomaInventario\Methods\TomaInventarioMethod;
use App\Models\TomaInventario\Attributes\TomaInventarioAttribute;
use App\Util\ModelUtil\ModelEmpresaScope;

class TomaInventario extends Model
{
  use
  UsesTenantConnection,
  ModelEmpresaScope,
  TomaInventarioAttribute,
  TomaInventarioMethod;


  protected $table = "productos_mat_inventario";
  protected $primaryKey = "InvCodi";
  public $presenter;
  
  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FModi"; 

  const ESTADO_PENDIENTE = "P";
  const ESTADO_CERRADO = "C";

  public $fillable = [
    "LocCodi",
    "InvFech",
    "InvNomb",
    "InvObse",
    "InvEsta",
    "empcodi",
    "panano",
    "mescodi",
    "user_Crea",
    "User_ECrea",
  ];

  public function __construct()
  {
    $this->presenter = new TomaInventarioPresenter($this);
  }

  public function repository()
  {
    return new TomaInventarioRepository($this) ;
  }

  public function local()
  {
    return $this->belongsTo( Local::class, 'LocCodi', 'LocCodi' );
  }

  public function detalles()
  {
    return $this->hasMany( TomaInventarioDetalle::class, 'InvCodi', 'InvCodi' );
  }

  public static function createDataForExcell($request)
  {
    $ti = new self();
    return [      
      'InvFech' => date('Y-m-d'),
      'LocCodi' => $request->local,
      'InvNomb' => $ti->getNextName(),
      // 'InvEsta' => $request->estado,
      'InvEsta' => self::ESTADO_CERRADO,
    ];
  }


}

