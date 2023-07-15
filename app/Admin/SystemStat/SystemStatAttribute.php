<?php

namespace App\Admin\SystemStat;

trait SystemStatAttribute
{
  public function getValueAttribute($value)
  {    
    return isJson($value) ? json_decode($value) : $value;
  }

  public function setValueAttribute($value)
  {
    $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
  }
}
