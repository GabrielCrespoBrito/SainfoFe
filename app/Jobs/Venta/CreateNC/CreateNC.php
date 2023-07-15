<?php

namespace App\Jobs\Venta\CreateNC;

use App\Venta;
use Illuminate\Support\Facades\DB;

class CreateNC
{
  /**
   *  Clase encargada de crear la NC
   * 
   */
  public $creator;


  public function __construct(Venta $documento, array $data)
  {
    $this->documento = $documento;
    $this->serie = $data['serieDocumento'];
    $this->data = $data;
    $this->creator = CreateNCProvider::GetCreator( $data['tipo'] , $documento, $data );
  }

  public function handle()
  {
    $this->creator;
    DB::connection('tenant')->beginTransaction();
    try {
      $this->creator->createDocumento();
      $this->creator->saveSerie();      
      $this->creator->createItems();
      $this->creator->createDataAssociate();
      DB::connection('tenant')->commit();
    } catch (\Throwable $th) {      
      DB::connection('tenant')->rollBack();
      return ['success' => false,  'errors' => $th->getMessage(),'error' => $th->getMessage()];
    }
     $this->creator->sendSunat();
    return ['success' => true, 'error' => null];
  }
}
