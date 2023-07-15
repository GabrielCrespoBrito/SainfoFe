<?php
namespace App\Helper\PDFDirect;

use Exception;

class LineSubtotal
{
  public $simbol;
  public $showSimbol = false;
  public $widthLine;
  public $widthRightCols = 25;

  public function __construct( $simbol = null, int $widthLine = 48, int $widthRightCols = 25 )
  {
    if ( $widthRightCols > $widthLine ) {
      throw new Exception("The width of the right column, can't by mayor of the width of line", 1);
    }

    $this->widthLine = $widthLine;
    $this->widthRightCols = $widthRightCols;

    if( $simbol ){
      $this->simbol = $simbol;
      $this->showSimbol = true;
    }
  }

  public function getSimbol()
  {
    return $this->simbol;
  }

  public function setSimbol($simbol)
  {
    $this->simbol = $simbol;

    return $this;
  }

  
  public function getAsString( $name = '', $price = '')
  {
    $leftCols = $this->widthLine - $this->widthRightCols;

    $left = str_pad($name, $leftCols);

    if ($this->showSimbol) {
      $simbolLen = strlen($this->getSimbol());
      $rightCols = $this->widthRightCols - $simbolLen;
      $right = $this->getSimbol() . str_pad($price, $rightCols, ' ', STR_PAD_LEFT);
    }
    else {
      $right = str_pad($price, $this->widthRightCols, ' ', STR_PAD_LEFT);
    }

    return "$left$right\n";
  }

  public function __toString()
  {
    return $this->getAsString();
  }
}
