<?php

namespace App\Woocomerce;

class ProcessResulterResolver
{
  // @TODO implementar cuando sean mas clases ademas de orders
  public static function get( $result, $woocomerceClass, $client )
  {
    return new ProcessResultOrder($result, $woocomerceClass, $client);
  }
}
