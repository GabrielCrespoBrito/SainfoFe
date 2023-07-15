<?php

namespace App\Http\Controllers\Proveedor;
use App\ClienteProveedor;

trait ProveedorTrait {

  public function searchByTerm($term){

    if (empty($term)) {
      return \Response::json([]);
    }

    $proveedores = ClienteProveedor::with('tipo_documento_c')        
    ->where([
      [ 'EmpCodi' , empcodi()],
      [ 'TipCodi' , 'P'],
      [ 'PCRucc' , 'like' , '%' . $term . '%'],
    ])->get();

    if(  !$proveedores->count() ){
      $proveedores = ClienteProveedor::with('tipo_documento_c')
      ->where([
        [ 'EmpCodi' , empcodi()],
        [ 'TipCodi' , 'P'],
        [ 'PCNomb' , 'like' , '%' . $term . '%'],
      ])->get();
    }

    return $proveedores;

  }


}