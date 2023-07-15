<?php

namespace App\Http\Requests;

trait SaverDataRequest
{
  protected $dataRequest = [];

  public function getData( $campo = null )
  {
    if($campo){
      return $this->dataRequest[$campo]; 
    }

    return (object) $this->dataRequest;
  }

  public function addData($name, $value)
  {
    $this->dataRequest[$name] = $value;
  }

  public function allWithAditional()
  {
    return array_merge( $this->all() , $this->dataRequest );
  }

}
