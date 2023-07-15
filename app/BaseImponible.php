<?php
namespace App;

class BaseImponible 
{
  const GRAVADA = "GRAVADA";
  const EXONERADA = "EXONERADA";
  const INAFECTA = "INAFECTA";
  const GRATUITA = "GRATUITA";

  public static function isGravada($base)
  {
    return self::GRAVADA == $base;
  }

}