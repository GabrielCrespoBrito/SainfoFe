<?php

namespace App\Repositories;

use App\CondicionDias;
use App\FormaPago;
use App\M;
use Illuminate\Support\Facades\Log;


class FormaPagoRepository extends RepositoryBase
{
  public function create($data, $code = null)
  {
    $isContado = $data['contipo'] == FormaPago::TIPO_CONTADO;

    $items = $isContado ? collect([['PgoDias' => 0,'PgoCodi' => null ]]) : collect($data['items']);
    $dias = $items->max('PgoDias');
    
    $model = $this->model;
    $model->conCodi = $code = $code ?? agregar_ceros(FormaPago::max('conCodi'), 2, 1);
    $model->contipo = $data['contipo'];
    $model->connomb = $data['connomb'];
    $model->condias = $dias;
    $model->system  = $data['system'] ?? 0;
    $model->save();


    $model->saveDias($items,true,$code);
  }


  public function update($data, $id)
  {
    $model = FormaPago::with('dias')->find($id);    
    $updatedTipo = $data['contipo'];
    $modelTipo = $model->contipo;

    $updateTipoIsCredito = $updatedTipo == FormaPago::TIPO_CREDITO;
    $items = $updateTipoIsCredito ? collect($data['items']) : collect([['PgoDias' => 0, 'PgoCodi' => null]]);

    $dias = $items->max('PgoDias');
    $model->contipo = $updatedTipo;
    $model->connomb = $data['connomb'];
    $model->condias = $dias;
    $model->save();

  
    # Si no se cambio el tipo y es credito
    if( $modelTipo == $updatedTipo  ){
      if(  $updateTipoIsCredito ){
      $model->deleteDias( $items );
      $model->saveDias($items,false, $model->conCodi);
      }
    }

    // Si se cambio de tipo
    else {
      $model->deleteDias();
      $model->saveDias($items,false, $model->conCodi);
    }

  }


}
