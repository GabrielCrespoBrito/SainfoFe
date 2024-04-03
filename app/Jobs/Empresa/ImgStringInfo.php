<?php

namespace App\Jobs\Empresa;

class ImgStringInfo
{
  public $img_str;
  public $info = [];

  const WIDTH_A4 = 920;
  const HEIGHT_A4 = 510;


  public function __construct($img_str)
  {
    $this->img_str = $img_str;

    $this->processInfo();

  }

  public function processInfo()
  {
    $img_info = [0,0];

    if( $this->img_str != "?" && $this->img_str != null ){
      $img_info = getimagesizefromstring($this->img_str);
    }

    $info = [
      'width'  => $img_info[0],
      'height' => $img_info[1],
    ];

    $info = array_merge( $info, $this->getImgPositionInPDF($info['width'],$info['height']) );

    $this->setInfo($info);
  }

  public function getImgPositionInPDF( $img_width , $img_height )
  {
    $starYPosition = 410;
    $starXPosition = 0;

    $top  = (self::HEIGHT_A4 - $img_height) / 2;
    $left = (self::WIDTH_A4 - $img_width) / 2;

    return [
      'top'  => $starYPosition + $top,
      'left' => $starXPosition + $left,
    ];
  }

  
  public function setInfo($info)
  {
    return $this->info = $info;
  }


  public function getInfo()
  {
    return $this->info;
  }
}