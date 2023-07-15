<?php
namespace App\Helper\PDFDirect;

use Exception;

class LineItemtotal
{
  public $lineWidth;
  public $totaltWidthtCol;
  public $widthtLastCol;
  
  public function __construct(int $lineWidth = 48, int $lineParts = 5)
  {
    $this->lineWidth = $lineWidth;
    $this->totaltWidthtCol = (int) ($lineWidth / $lineParts);
    $this->widthtLastCol = $this->totaltWidthtCol;  

    /* La converciòn a decimal de (int) redondea hacia abajo, por tanto cuando la particiòn de columnas resulte en decimal 48/5 (9.6), el total de las columnas no correspondera al total (9*5= 45 != 48)
    */
    if( ($widthMultipledTotal = $this->totaltWidthtCol * $lineParts) !== $lineWidth ){
      $this->widthtLastCol += ($lineWidth - $widthMultipledTotal);
    }
  }

  public function getAsString( ...$totals )
  {
    $str = "";
    $lengthTotals = count($totals);
    for ( $i = 0; $i < $lengthTotals; $i++) {
      $widthColumn = $i == $lengthTotals-1 ? $this->widthtLastCol : $this->totaltWidthtCol;
      $str .= str_pad($totals[$i], $widthColumn, ' ',  STR_PAD_LEFT);
    }

    return "$str\n";
  }
}
