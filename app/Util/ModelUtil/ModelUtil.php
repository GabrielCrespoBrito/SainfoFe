<?php

namespace App\Util\ModelUtil;

use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ModelUtil
{
  /**
   * Obtener el nombre real de un campo del 
   * 
   * @param string $field  - Nombre del campo
   * @return string|null
   */
  public function getRealFieldName($field)
  {
    $lenField = strlen($field);
    $keyString = implode(" ", array_keys($this->getAttributes()));
    $pos = stripos( $keyString , $field );
    return $pos !== false ? substr( $keyString , $pos , $lenField ) : false;    
  }

  public function getEmpresaIDAttribute()
  {
    $empresaField = $this->getRealFieldName('empcodi');
    return $this->{$empresaField};
  }

  /**
   * Eliminar datos de una relacion hasMany de un modelo
   * @param HasMany $relation
   * @param callback|null $closure
   * 
   * @return self
   */
  public function deleteMany( $relation , callable $closure = null )
  {
    if (is_null($this->{$relation})) {
      throw new Exception("The {$relation} is not a property or method of this model", 1);
    }

    if ($this->{$relation} instanceof HasMany) {
      throw new Exception("the {$relation} is not a relation", 1);
    }
    
    foreach ($this->{$relation} as $model) {
      
      if( $deleting = $closure ){
        $deleting = $closure($model);
      }

      if( $deleting === false ){
        continue;
      }

      $model->delete();
    }

    return $this;
  }

  /**
   * Obtener cantidad de cero que se quiere utilizar para el correlativo de un campo
   * 
   * @return int
   */
  public function quantityCeroCorrelative($field): int
  {
    $name = $field . 'Cero';
    return $this->{$name} ?? 3;
  }

  /**
   * Poner ultimo codigo de un campo determinado
   * 
   * @param string $field
   * @param array $filters
   * @param array $boolean
   * @return mixed|null
   */

  public function getLast(String $field, array $filters = [])
  {
    $model = new self();

    # Filtrar    
    if (count($filters)) {
      $model = $model->where($filters);
    }

    # Obtener valor
    return optional($model->orderBy($field, 'desc')->first())->{$field};
  }

  /**
   * Incrementar el valor del ultimo codigo de un campo
   * @param $field  Campo a buscar
   * @param $filters  Filtros a utilizar
   */
  public function getLastIncrement(String $field, array $filters = [])
  {
    $result = $this->getLast($field, $filters);
    
    $hasValue = true;

    # Si no tiene valor, poner 0 por defecto
    if (is_null($result)) {
      $result = 0;
      $hasValue = false;
    }
    
    # Verificar que sea un numero
    $isIncrementable = is_numeric($result);

    # Si es un valor tipo A50 o 31A, devolverlo
    if (!$isIncrementable) {
      return $result;
    }
    # Si es el caso de 
    $ceroQuantity = $hasValue ? strlen($result) : $this->quantityCeroCorrelative($field);
    return math()->addCero(math()->increment($result) , $ceroQuantity );
  }


  public function getDescripcionAttribute()
  {
    if( $this->descripcionKey == null ){
      throw new Exception("descripcionKey dont property dont exists", 1);      
    }

    return $this->{$this->descripcionKey};
  }

  public function copyTo( $empcodi , $empcodiField ,  $othersField = [] )
  {
    self::unguard();
    $registers = $this->where($empcodiField, "001")->get();

    foreach( $registers as $register ){
      $data = $register->toArray();
      $data[$empcodiField] = $empcodi;
      $data = array_merge($data , $othersField);
      self::create($data);
    }
  }
}