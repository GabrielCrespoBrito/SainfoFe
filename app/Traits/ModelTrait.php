<?php

namespace App\Traits;

trait ModelTrait {

  public function quantityCeroCorrelative()
  {
    # Test    
    return $this->ceroCorrelative ?? 3;
  }

  public function deleteDb()
  {
    $this->UDelete = "*";
    $this->save();
  }

  public function deleteRevert()
  {
    $this->UDelete = 0;
    $this->save();
  }

  /**
   * Poner ultimo codigo de un campo determinado
   * 
   * @return mixed
   */
  public static function getLastCode( $field , $filters = [], $incrementing = false )
  {
    $query = new self();

    # Filtrar    
    if( count($filters) ){
      $query = $query->where($filters);
    }

    $hasValue = true;

    # Obtener valor
    $result = $resultInitial = optional($query->orderBy($field,'desc')->first())->{$field};


    # Poner valor inicial si no existe
    if( is_null($result) ) {         
      $result = 0;
      $hasValue = false;
    }

    # Verificar que sea un numero
    $isIncrementable = is_numeric($result);

    # Incrementar valor 
    if( $incrementing && $isIncrementable ){
      $result = intval($result) + 1;
    }

    # 
    if( $isIncrementable  ){
      $quantityCeros = $hasValue ? strlen($resultInitial) : $this->quantityCeroCorrelative();
      $result = str_pad($result, $quantityCeros , '0', STR_PAD_LEFT);
    }

    return $result; 
  }



};



