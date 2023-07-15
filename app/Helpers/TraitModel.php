<?php

namespace App\Helpers;
use Illuminate\Database\Eloquent\Builder;

trait ModelHelper 
{
  public function idNext()
  {
    return agregar_ceros( $this->max( $this->primaryKey ) , $this->cantCeros );
  }

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

  protected function getKeyForSaveQuery($keyName = null)
  {
    if(is_null($keyName)){
      $keyName = $this->getKeyName();
    }
    if (isset($this->original[$keyName])) {
      return $this->original[$keyName];
    }
    return $this->getAttribute($keyName);
  }


}