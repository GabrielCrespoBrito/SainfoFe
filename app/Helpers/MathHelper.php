<?php

namespace App\Helpers;

use Exception;

class MathHelper
{
  protected $currentValue = false;

  // Numero a decimales  
  private $fixedDecimals;
  private $roundNumber;

  public function __construct( $fixedDecimals = false , bool $roundNumber = false)
  {
    $this->fixedDecimals = $fixedDecimals;
    $this->roundNumber = $roundNumber;
  }

  public function getCurrentValue()
  {
    return $this->currentValue;
  }

  /**
   * setCurrentValue
   * 
   * @return void
   */
  public function setCurrentValue($currentValue)
  {
    $this->currentValue = $currentValue;
  }  

  public function getOrErrorCurrentValue()
  {
    if( $this->getCurrentValue() === false ){
      throw new Exception("Dont has value to give", 1);      
    }

    return $this->getCurrentValue();
  }


  // Agregar decimales a un numero
  public function addDecimal($value , $decimals = 2)
  {
    return number_format((float) $value, $decimals, '.', '');
  }

  public function addDecimal_o($value, $decimals)
  {
    return number_format((float) $value, $decimals, '.', '');
  }


  public function internalDecimal($value)
  {
    return $this->fixedDecimals ? $this->addDecimal( $value , $this->fixedDecimals ) : $value;
  }

  public function decimal( $value , $decimal = false )
  {
    return number_format((float) $value, $decimal, '.', '');
  }
  
  public function sum( ...$args )
  {
    return $this->internalDecimal(collect($args)->sum());
  }

  public function makeOper( $oper , $args )
  {
    $args = $this->prepareArgs( $args );

    $res = $this->$oper($args);

    $this->setCurrentValue($res);

    return $this;
  }

  // public function __get($name)
  // {
  // }


  public function divisionOrCero($val1,$val2)
  {
    $val1 == (float) $val1;
    $val2 == (float) $val2;

    return ! $val1 || ! $val2  ? 0 : ($val1 / $val2);
  }


  public function res(...$args)
  {
    return $this->internalDecimal(collect($args)->res());
  }

  public function division( $param1 , $param2 )
  {
    return $this->internalDecimal($param1 / $param2);
  }

  public function avg(...$args)
  {
    // return $this->internalDecimal((collect($args)->avg() );
  }

  public function baseCero( int $val )
  {
    return $val < 10 ? ('0'.$val) : $val;
  }

  public function baseUno($val)
  {
    $val = (float) $val;
    return ($val /100) + 1;
  }


  public function baseCerapio($val)
  {
    return (float) $val / 100;
  }



  /**
   * Calcular el porcentaje de una cifra con respecto a otra
   * Ejemplo 10% de 120 = 12
   */
  public function porc($perc, $value)
  {
    return ($value / 100) * $perc;
  }

  /**
   * Calcular el porcentaje de una cifra y sumarsela a la misma
   * @example  Ejemplo (A % B) + B 
   * @example  Ejemplo (5 % 100 = 5) + 100 = 105
   * 
   */
  public function porcAndSum($perc, $value)
  {
    return $this->porc( $perc, $value ) + $value;
  }


  /**
   * Obtener el porcentaje inverso
   */
  public function porcInverse($param1, $param2)
  {
    return ($param1 / $param2) * 100;
  }

  public function integer( $val )
  {
    return (int) $val;
  }

  /**
   * Obtener el factor de multiplicacion de una cifra para obtener un porcentaje
   * 
   * @return String
   * @example 27.8 = 0.278 
   * @example 18 = 0.18
   * @example 1 = 0.01 
   * @example 0.5 = 0.005 
   */
  public function porcFactor( $param1 ) : String
  {
    return $param1 / 100;
  }

  /**
   * Quitar punto y/o comas de una cifra
   */
  public function pointLess( $val )
  {
    return str_replace(",", "", str_replace(".", "", $val));
  }

  /**
   * De una cifra obtener el favor de divisor
   * 
   * @example 12.7 = 1.127 
   * @example 0.5 = 1.05
   * @return float
   */
  public function factorDivider($val)
  {
    return (float) ("1." . $this->pointLess($val));
  }

  /**
   * 
   * @example $val(100) $porc(18) = 100/1.18
   * @example .5 = 1.05
   * @return float
   */
  public function dividerPorc($val, $porc)
  {
    return $this->division( $val, $this->factorDivider($porc) );
  }

  /**
   * 
   * @example $val(100) $porc(18) = 100/1.18
   * @example .5 = 1.05
   * @return float
   */
  public function multiplicarPorc($val, $porc)
  {
    return $val * $this->factorDivider($porc);
  }


  public function addCero($val, int $quantity, bool $after = true): String
  {
    $len = strlen((string) $val);

    $ceros = "";

    if ($len < $quantity) {
      $ceros = str_repeat("0", ($quantity - $len));
    }

    return $after ? ($ceros . $val) : ($val . $ceros);
  }

  public function increment($val)
  {
    if (!is_numeric($val) && is_string($val)) {
      throw new Exception("Only numbers|bool|null", 1);
    }
    
    # Poner valor inicial cuando no sea numerico
    if (!is_numeric($val)) {
      $val = (int) $val;
    }

    # Incrementar valor 
    return ($val + 1);
  }


  public function isPositive($val)
  {
    return (float) $val > 0;
  }

  /**
   * Funcion para multiplicar o dividir una cifra segun el valor pasado pro la funcion
   * 
   * 
   */
  public function mulOrDiv( bool $multiplicar = true, $param1, $param2 )
  {
    return $multiplicar ? $param1 * $param2 : $param1 / $param2;
  }

}