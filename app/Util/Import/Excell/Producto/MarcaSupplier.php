<?php

namespace App\Util\Import\Excell\Producto;

use App\Marca;

class MarcaSupplier extends SupplierAbstract
{
  const NUMBER_INIT = 100;

  public function setInitData()
  {
    $this->entidadData = Marca::all()->sortBy('MarCodi')->pluck('MarCodi', 'MarNomb')->toArray();
  }

  public function getLastId()
  {
    $marcodi = last( $this->entidadData );

    if( is_numeric($marcodi) ){
      return math()->addCero($marcodi+1, 2);
    }

    $last_id_numeric = -1;

    foreach( $this->entidadData as $id ){
      
      if( !is_numeric($id)){
        continue;
      }

      $last_id_numeric = $id > $last_id_numeric ? $id : $last_id_numeric;
    }

    if( $last_id_numeric === -1 ){
      return '00';
    }

    return math()->addCero($last_id_numeric+1, 2);
  }

  public function createEntidad()
  {
    $id = $this->getLastId();

    Marca::create([
      'MarNomb' => $this->campoValue,
      'MarCodi' => $id,
      'empcodi' => $this->empcodi,
    ]);

    // Add al array de marcas
    return $this->entidadData[$this->campoValue] = $id;
  }

  public function getOrCreate($dataProcess)
  {
    return $this->entidadData[$this->campoValue] ?? $this->createEntidad();
  }

  public function handle(&$dataProcess)
  {
    $dataProcess[$this->getHeader()] = $this->getOrCreate($dataProcess);
  }
}
