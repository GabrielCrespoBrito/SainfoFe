<?php

namespace App\Models\Suscripcion;

use App\User;
use App\Empresa;
use Illuminate\Database\Eloquent\Model;
use App\Models\Suscripcion\PlanDuracion;
use App\Jobs\Suscripcion\CreateSuscripcion;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class OrdenPago extends Model
{
  use UsesSystemConnection;
  protected $table = "suscripcion_system_ordenes_pago";
  protected $fillable = [
    'duracion_id',
    'empresa_id',
    'user_id',
    'descuento_porc',
    'descuento_value',
    'base',
    'igv',
    'total',
    'fecha_emision',
    'fecha_vencimiento',
    'estatus',
  ];

  const PAGADA = "pagada";
  const PENDIENTE = "pendiente";

  public function isPagada()
  {
    return $this->estatus == self::PAGADA;
  }

  public function isPendiente()
  {
    return $this->estatus == self::PENDIENTE;
  }

  public static function createFromPlanDuracion(PlanDuracion $planduracion, $empresa_id,  $user_id, $status_pagada = null)
  {
    $data = $planduracion->toArray();

    unset($data['plan_id']);
    unset($data['codigo']);
    unset($data['id']);

    $data['fecha_emision'] = datePeru('Y-m-d h:m:s');
    $data['fecha_vencimiento'] = null;
    $data['duracion_id'] = $planduracion->id;
    $data['empresa_id'] = $empresa_id;
    $data['user_id'] = $user_id;

    if($status_pagada ){
      $data['estatus'] = OrdenPago::PAGADA;
    }

    return  self::create($data);
  }


  public function suscripcion()
  {
    // return $this->belongsTo( Suscripcion::class, 'orden_id' );
    return $this->belongsTo(Suscripcion::class, 'id', 'orden_id');
  }

  public function planduracion()
  {
    return $this->belongsTo(PlanDuracion::class, 'duracion_id');
  }

  public function empresa()
  {
    return $this->belongsTo(Empresa::class, 'empresa_id', 'empcodi');
  }

  public function getBaseAttribute($val)
  {
    return decimal($val);
  }

  public function getIgvAttribute($val)
  {
    return decimal($val);
  }

  public function getTotalAttribute($val)
  {
    return decimal($val);
  }

  public function getDescuentoValueAttribute($val)
  {
    return decimal($val);
  }


  public function getIdFormat()
  {
    return $this->uuid;
  }

  public function getFile()
  {
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'usucodi');
  }

  public function fileName($ext = 'pdf')
  {
    return 'ordenpago_' . $this->getIdFormat() . '.' . $ext;
  }

  public function getNombrePlan()
  {
    return $this->planduracion->nombreCompleto();
  }

  public function dataPDF()
  {
    $empresa_fac = Empresa::getEmpresaFacturadora();

    $empresa = $this->empresa;

    $logo = $empresa_fac->logoEncode(1);
    $ruc = $empresa_fac->ruc();
    $razon_social = $empresa_fac->nombre();
    $direccion = $empresa_fac->direccion();
    $telefonos = $empresa_fac->telefonos();
    $email = $empresa_fac->email();
    $nrOrden = $this->getIdFormat();
    $razon_social_cliente = $empresa->nombre();
    $ruc_cliente = $empresa->ruc();
    $direccion_cliente = $empresa->direccion();
    $fecha_emision = $this->fecha_emision;
    $fecha_vencimiento = $this->fecha_vencimiento;

    return [
      'logo' => $logo,
      // Empresa
      'ruc' => $ruc,
      'razon_social' => $razon_social,
      'direccion' => $direccion,
      'telefonos' => $telefonos,
      'email' => $email,
      'nrOrden' => $nrOrden,

      'razon_social_cliente' => $razon_social_cliente,
      'ruc_cliente' => $ruc_cliente,
      'direccion_cliente' => $direccion_cliente,

      'fecha_emision' => $fecha_emision,
      'fecha_vencimiento' => $fecha_vencimiento,

      'codigo' => $this->getIdFormat(),
      'nombre' => $this->getNombrePlan(),
      'cantidad' => 1,
      'precio' => $this->base,
      'igv' => $this->igv,
      'descuento' => $this->descuento_value,
      'total' => $this->total,
    ];
  }


  public function createSuscripcion()
  {
    CreateSuscripcion::dispatchNow($this);
  }


  public function empresaRequiredConfigIsNecesary()
  {
  }
}
