<?php

namespace App\Admin\SystemStat;

use App\Repositories\SystemStatRepository;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class SystemStat extends Model
{
  use UsesSystemConnection;
  use SystemStatAttribute;
  use SystemStatMethod;
  
  protected $table = 'system_stats';
  protected $data = null;  
  public $fillable = ['value', 'updated_at'];

  const EMPRESAS_ACTIVAS = 'empresas_activas';
  const VENTAS_TOTALES = 'ventas_totales';
  const COMPRAS_TOTALES = 'compras_totales';
  const USUARIOS_ACTIVOS = 'usuarios_activos';
  const SYSTEM_STADISTICAS = 'system_estadisticas';
  
  const DOCUMENTOS_PENDIENTES = "documentos_pendientes";
  const GUIAS_POR_ENVIAR = "guias_pendientes";
  const RESUMENES_POR_VALIDAR = "resumenes_pendientes";
  const PROCESAR_ORDENES_EMPRESA = "procesar_ordenes_empresa";

  # Pendientes
  const EMPRESAS_VENTAS_PENDIENTES = "empresas_ventas_pendientes";
  const EMPRESAS_GUIAS_PENDIENTES = "empresas_guias_pendientes";
  const EMPRESAS_RESUMENES_PENDIENTES = "empresas_resumenes_pendientes";

  
  # Acciones pendientes del sistemas: enviar_documentos, Enviar resumenes, Enviar Guias, Procesar Orden de Pago
  const ACCIONES_PENDIENTES = "acciones_pendientes";
  
  public function repository()
  {
    return new SystemStatRepository($this);
  }

  public static function findByName($name)
  {
    return self::where('name' , $name)->first();
  }  
}