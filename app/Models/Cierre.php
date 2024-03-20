<?php

namespace App\Models;

use App\Venta;
use App\Repositories\CierreRepository;
use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use App\Jobs\EstadisticasMensual\CrearStats;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Jobs\EstadisticasMensual\CreateStats;
use App\Jobs\EstadisticasMensual\CrearEstadisticas;
use App\Jobs\EstadisticasMensual\EstadisticaByFechas;

class Cierre extends Model
{
  use
    UsesTenantConnection,
    ModelEmpresaScope;

  protected $table = "cierres";
  public $timestamps = false;
  public $casts = [
    'estadistica' => 'array'
  ];  
  const EMPRESA_CAMPO = "empcodi";

  public function getEstado()
  {
    return $this->exists ? 'Cerrado' : 'Abierto';
  }

  public function repository()
  {
    return new CierreRepository($this);
  }

  public static function getFechaUpdate($mescodi)
  {
    $mes = self::findByMes($mescodi);

    if( $mes == null || optional($mes)->estadistica == null ){
      return false;
    }

    // dd( $mes->estadistica );
    // exit();

    return $mes->estadistica['busqueda']['ventas_sunat'] ?? false;
  }


  public static function findByMes($mescodi)
  {    
    return self::where('mescodi' , $mescodi)->first();
  }

  public function getOrCreateStadistics($searchSunat = false, $onlyVenta = false)
  {
    return self::crearEstadisticas($this->mescodi, $this, $searchSunat, $onlyVenta);
  }

  public static function crearEstadisticas($mescodi, $mes = null, $searchSunat = null, $onlyVenta = false)
  {
    return (new CreateStats($mescodi, $mes, $onlyVenta, $searchSunat ))->handle();
  }

  public static function getStadistics( $mescodi, $searchSunat = null )
  {
    $mes = self::findByMes($mescodi);

    if( $mes ){
      return $mes->getOrCreateStadistics($searchSunat);
    }
    
    return self::crearEstadisticas($mescodi, null, $searchSunat);
  }

  public static function getStadisticsByFechas($fecha_desde, $fecha_hasta)
  {
    return (new EstadisticaByFechas( $fecha_desde, $fecha_hasta ))->handle();
  }


  public function addToStadistics( Venta $venta )
  {

  }

  public static function estaAperturadoPorFecha($fecha)
  {
    $data = get_date_info($fecha);
    $cierre = Cierre::where('mescodi', $data->mescodi)->first();

    if( $cierre ){
      return $cierre->hasFechaCerrado();
    }

    return false;
  }

  public static function estaAperturado($mescodi)
  {
    return (int) Cierre::where('mescodi', $mescodi)->count();
    // return (int) Cierre::where('mescodi', $mescodi)->count();
  }

  public static function createByMescodi($mescodi, $conFechaCierre = true, $estadistica = null)
  {
    $cierre = new Cierre();
    $cierre->empcodi = empcodi();
    $cierre->mescodi = $mescodi;
    $cierre->usucodi = auth()->user()->usucodi;
    $cierre->estadistica = $estadistica;
    $cierre->fecha_cierre = $conFechaCierre ? date('Y-m-d H:i:s') : null;
    $cierre->save();
  }
  
  public function cerrar()
  {
    $this->fecha_cierre = date('Y-m-d H:i:s');
    $this->save();
  }

  public function hasFechaCerrado()
  {
    return $this->exists ? ! is_null($this->fecha_cierre)  : false;
  }

  public static function createIfNotExists($mescodi)
  {
    $cierre = Cierre::where('mescodi', $mescodi)->count();

    if( $cierre == 0 ){
      $cierre = new Cierre();
      $cierre->empcodi = empcodi();
      $cierre->mescodi = $mescodi;
      $cierre->usucodi = auth()->user()->usucodi;
      $cierre->fecha_cierre = date('Y-m-d H:i:s');
      $cierre->save();
    }
  }
}
