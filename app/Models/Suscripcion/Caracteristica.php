<?php

namespace App\Models\Suscripcion;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Caracteristica extends Model
{
  use UsesSystemConnection;
     
  protected $table = "suscripcion_system_caracteristicas";
  
  # Listado de caracteristicas del sistema
  const COMPROBANTES = 'comprobantes';
  const PRODUCTOS = 'productos';
  const USUARIOS = 'usuarios';  
  const LOGOTIPO = 'logotipo';
  const ENVIO = 'envio';
  const REPORTES = 'reportes';
  const SUNAT = 'sunat';
  const CERTIFICADO = 'certificado';
  const CATALOGO = 'catalogo';
  const LOCAL = 'local';
  
  # Tipo de consumo
  const CONSUMO = "consumo";
  const RESTRICCION = "restriccion";

  protected $fillable = [
    'nombre',
    'codigo',
    'value',
    'tipo',
    'active',
    'primary',
    'reset',
    'parent_id',
    'adicional',
  ];

  public function isTipoConsumo()
  {
    return $this->tipo == self::CONSUMO;
  }

  public function isReset()
  {
    return $this->reset == 1;
  }

  public function hasMessage()
  {
    return !is_null($this->adicional);
  }

  public function isMaestro()
  {
    return $this->parent_id == null;
  }

  public function plan_caracteristica()
  {
    return $this->hasOne(PlanCaracteristica::class, 'caracteristica_id' , 'id');
  }

  public function caracteristica_childs()
  {
    return $this->hasMany(Caracteristica::class, 'parent_id' , 'id');
  }

  # ....Okay....
  public static function getNombre($caracteristica)
  {
    switch ($caracteristica)
    {
      case self::COMPROBANTES:
        return self::COMPROBANTES;
      break;
      case self::USUARIOS:
        return self::USUARIOS;
      break;
      case self::LOCAL:
        return self::LOCAL;
      break;
      case self::PRODUCTOS:
        return self::PRODUCTOS;
      break;
      default:
      throw new Exception("Dont exist feature {$caracteristica}", 1);
      break;
    }    
  }
}