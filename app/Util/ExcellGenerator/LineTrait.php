<?php

namespace App\Util\ExcellGenerator;


trait LineTrait
{
  public function setStarLine($linea)
  {
    return $this->linea = $linea ;
  }

  public function getLinea()
  {
    return $this->linea;
  }

  public function getLineaAndSum(int $int = 1)
  {
    $lastLine = $this->getLinea();
    $this->sumLinea($int);
    return $lastLine;
  }

  public function setLinea(int $linea)
  {
    $this->linea = $linea;
    return $this;
  }

  public function sumLinea(int $int = 1)
  {
    $this->linea += $int;
    return $this;
  }

  public function getTextWihLinea($str, $line = null)
  {
    $line = $this->getLinea() ?? $line;
    return str_replace('%l', $line, $str);
  }

  public function getLastLine()
  {
    return $this->getLinea() - 1;
  }

}
