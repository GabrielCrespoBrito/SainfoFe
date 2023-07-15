<?php

namespace App;

class UnidadFake
{
  public $factor;
  public $UniAbre;
  public $UniPeso = 0;


  public function setData($factor, $abreviatura, $peso = 0)
  {
    $this->factor = $factor;
    $this->UniAbre = $abreviatura;
    $this->UniPeso = $peso;
  }
  public function getFactor()
  {
    return $this->factor;
  }




}
