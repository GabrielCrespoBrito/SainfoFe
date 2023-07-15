<?php

namespace App\Observers;

use App\Vendedor;

class VendedorObserver
{
  public function creating(Vendedor $vendedor)
  {
    $vendedores = Vendedor::all()->filter(function($model,$key){
      return is_numeric($model->Vencodi);
    });

    $id = 0;

    if($vendedores->count()){
      $id = (int) $vendedores->max('Vencodi');
    }

    $id = agregar_ceros($id,4,1);
    $vendedor->Vencodi = $id;
    $vendedor->empcodi = empcodi();
  }

}