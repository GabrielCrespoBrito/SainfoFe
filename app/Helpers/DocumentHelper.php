<?php

namespace App\Helpers;

use Carbon\Carbon;

class DocumentHelper
{
  /**
   * Si un documento esta dentro de un plazo
   * 
   * @param string $fecha 
   * @param int $dias 
   * @param bool $after 
   * 
   * @return bool
   */
  public function enPlazo($fecha, $dias, $after = true)
  {
    $carbon = $after ? (new Carbon($fecha))->addDays($dias) : (new Carbon($fecha))->subDays($dias);
    return $after ? $carbon->isAfter(date('Y-m-d')) :  $carbon->isBefore($fecha);
  }

  public function enPlazoDeEnvio($tipo_documento,  $fecha)
  {
    $dias = $this->getDiasPlazo($tipo_documento);
    $carbon = new Carbon($fecha);
    return $carbon->addDays($dias)->isBefore(date('Y-m-d'));
  }

  public function getDiasPlazo($tidcodi)
  {
    $name = 'dias_envio_documento_' . $tidcodi;
    return get_setting($name);
  }

  public function sobrePasaDias($fechaDesde, $fechaHasta, $dias)
  {
    $fechaDesde = new Carbon($fechaDesde);
    $fechaHasta = new Carbon($fechaHasta);
    return $fechaDesde->addDays($dias)->isBefore($fechaHasta);
  }

  public function getFechaLimite($tidcodi)
  {
    $dias = $this->getDiasPlazo($tidcodi);
    $carbon = new Carbon(date('Y-m-d'));
    $carbon->subDays( $dias );
    return $carbon->format('Y-m-d');
  }
}
