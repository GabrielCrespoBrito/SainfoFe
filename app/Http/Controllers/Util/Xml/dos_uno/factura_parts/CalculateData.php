<?php

namespace App\Http\Controllers\Util\Xml\dos_uno\factura_parts;

trait CalculateData
{
  /**
   * Si el documento es una Nota de Credit con dcto Global, aplicar ese porcentaje de Dcto a los valores que se le pasen a esta funciòn
   * 
   * @return String
   */
  public function convertValuesIfNCWithDiscounts($value)
  {
    if( $this->aplly_dcto_global_items ) {
      return $value - (($value / 100) * $this->documento->globalPorc());
    }

    return $value;

  }

}