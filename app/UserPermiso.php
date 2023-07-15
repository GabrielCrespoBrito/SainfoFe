<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserPermiso extends Model
{
  protected $table = "model_has_permissions";

  // protected $primaryKey = "PCCodi";
  protected $primaryKey = ["permission_id", "model_id"];

  /**
   * Set the keys for a save update query.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  protected function setKeysForSaveQuery(Builder $query)
  {
    $keys = $this->getKeyName();
    if(!is_array($keys)){
      return parent::setKeysForSaveQuery($query);
    }

    foreach($keys as $keyName){
      $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
    }

    return $query;
  }


}