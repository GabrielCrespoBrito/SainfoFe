<?php

namespace App\Util\Import\Excell;

use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Util\Import\Excell\Producto\ResultTrait;
use App\Util\Import\Excell\Producto\StoreProducts;
use Maatwebsite\Excel\Collections\SheetCollection;
use App\Util\Import\Excell\Producto\ValidateProducto;


class ImportExcellProducts
{
  use ResultTrait;

  /**
   * Data del Excell
   *  
   * */  
  protected $data;


  protected $excell;

  public function __construct( $excell )
  {
    $this->setData($excell);
  }

  public function setData($excell)
  {
    $this->data = Excel::load($excell->getRealPath())->get();
  }

  public function validate()
  {
    $validator = new ValidateProducto($this->data);
    $result = $validator
    ->handle()
    ->getResult();    

    if( ! $result->success ){
      throw new Exception(implode('| ', $result->errors), 1);
    }

    return $result->success;
  }

  public function store()
  {
    $result = (new StoreProducts($this->data))
    ->handle()
    ->getResult();

    if (!$result->success) {
      return $this->addError($result->errors);
    }

    return true;
  }

  public function handle()
  {
    if( $this->validate() == false ){
      return $this;
    }

    $this->store();

    return $this;      
  }  
}
