<?php

namespace App;

use App\Repositories\TipoIGVRepository;
use App\Venta;
use Illuminate\Database\Eloquent\Model;

class TipoIgv extends Model
{
  protected $connection = 'mysql';
  protected $table = 'tipos_igvs';
  public $timestamps = false;
  const DEFAULT_GRAVADA = "10";
  const DEFAULT_EXONERADA = "20";
  const DEFAULT_INAFECTA = "30";
  # El tipo que se puede utilizar para los items gratuitos
  const GRATUITO_DISPONIBLE = 1;

  const GRATUITAS_GRAVADAS = [
    '11',
    '12',
    '13',
    '14',
    '15',
    '16',
    '17',
  ];


  const BASES = [
    '10' => 'GRAVADA', // '0', 'Gravado - Operación Onerosa'
    '11' => 'GRAVADA', // '1', 'Gravado - Retiro por premio'
    '12' => 'GRAVADA', // '1', 'Gravado - Retiro por donación'
    '13' => 'GRAVADA', // '1', 'Gravado - Retiro'
    '14' => 'GRAVADA', // '1', 'Gravado - Retiro por publicidad'
    '15' => 'GRAVADA', // '1', 'Gravado - Bonificaciones'
    '16' => 'GRAVADA', // '1', 'Gravado - Retiro por entrega a trabajadores'
    '17' => 'GRAVADA', // '1', 'Gravado - IVAP'
    '20'  => 'EXONERADA', // '0', 'Exonerado - Operación Onerosa'
    '21'  => 'EXONERADA', // '1', 'Exonerado - Transferencia Gratuita'
    '30'  => 'INAFECTA', // '0', 'Inafecto - Operación Onerosa'
    '31'  => 'INAFECTA', // '1', 'Inafecto - Retiro por Bonificación'
    '32'  => 'INAFECTA', // '1', 'Inafecto - Retiro'
    '33'  => 'INAFECTA', // '1', 'Inafecto - Retiro por Muestras Médicas'
    '34'  => 'INAFECTA', // '1', 'Inafecto - Retiro por Convenio Colectivo'
    '35'  => 'INAFECTA', // '1', 'Inafecto - Retiro por premio'
    '36'  => 'INAFECTA', // '1', 'Inafecto - Retiro por publicidad'
    '40'  => 'INAFECTA', // '1', 'Exportación'
  ];


  /**
   * Si es de tipo gravado
   * 
   */
  public static function isTipoGravada($tipo)
  {
    return 
      $tipo == '10' ||
      $tipo == '11' ||
      $tipo == '12' ||
      $tipo == '13' ||
      $tipo == '14' ||
      $tipo == '15' ||
      $tipo == '16' ||
      $tipo == '17';
  }

  /**
   * Saber si el tipo de igv es gravado
   * 
   */


  public static function getCodeSunat($base, $code = null)
  {
    switch ($base) {
      case Venta::GRAVADA:
        return self::DEFAULT_GRAVADA;
        break;
      case Venta::EXONERADA:
        return self::DEFAULT_EXONERADA;
        break;
      case Venta::INAFECTA:
        return self::DEFAULT_INAFECTA;
        break;
      default:
        return $code;
        break;
    }
  }

  public static function getRealBase($tipoIGV)
  {
    return self::BASES[$tipoIGV];
  }



  /**
   * @TODO Mover a Scope
   * 
   * 
   * @return 
   */
  public function scopeGratuitoDisponible($query)
  {
    return $query->where('gratuito_disponible', self::GRATUITO_DISPONIBLE);
  }

  public function repository()
  {
    return new TipoIGVRepository($this);
  }
}
