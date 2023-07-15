<?php

namespace App;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class MotivoTraslado extends Model
{
  use UsesSystemConnection;
  protected $table = 'motivo_traslado';
  
    const VENTA = '01';
    const COMPRA = '02';
    const TRASLADO_MISMA_EMPRESA = '04';
    const IMPORTACION = '08';
    const EXPORTACION = '09';
    const OTROS = '13';
    const VENTA_SUJETA_A_CONFIRMACION = '14';
    const TRASLADO_EMISOR_ITINERANTE = '18';
    const TRASLADO_ZONA_PRIMERA = '19';


  /**
   * Undocumented function
   *
   * @return boolean
   */
  public static function isWithProveedor($tipo)
  {
    return $tipo == self::COMPRA || $tipo == self::IMPORTACION;
  }

  /**
   * Undocumented function
   *
   * @return boolean
   */
  public function isProveedor()
  {
    return self::isWithProveedor($this->MotCodi);
  }




}
