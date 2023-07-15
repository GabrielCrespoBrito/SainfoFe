<?php
namespace App\Helper\PDFDirect;

use Exception;

class LineItemHeader
{
  public $lineWidth;
  public $leftWidthtCols;
  
  public function __construct( int $lineWidth = 48, int $leftWidthtCols = 6 )
  {
    $this->lineWidth = $lineWidth;
    $this->leftWidthtCols = $leftWidthtCols;
  }

  
  public function getAsString( $leftStr = '', $rightStr = '')
  {
    $rightCols = $this->lineWidth - $this->leftWidthtCols;

    $left = str_pad( $leftStr, $this->leftWidthtCols , ' ',  STR_PAD_RIGHT );
    $right = str_pad($rightStr, $rightCols, ' ', STR_PAD_RIGHT );

    return "$left$right\n";
  }
}
