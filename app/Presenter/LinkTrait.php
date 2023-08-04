<?php

namespace App\Presenter;

trait LinkTrait
{
  public function linkEdit( $text = null, $className = "")
  {
    $text = $text ?? $this->getDescripcion();

    return sprintf("<a href='%s'> %s </a>" , $this->routeEdit( $this->getId()), $text );
  }
}
