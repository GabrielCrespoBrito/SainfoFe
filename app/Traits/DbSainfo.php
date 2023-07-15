<?php

namespace App\Traits;

trait DbSainfo {

  /**
   * Devolver la llave principal 
   *
   * @return mixed
   */
  public function getIdAttribute()
  {
    return $this->{$this->primaryKey};
  }

}
