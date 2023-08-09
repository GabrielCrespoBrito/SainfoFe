<?php

namespace App\Repositories;

use App\Models\Produccion\Produccion;
use App\Models\Produccion\ProduccionDetalle;
use App\Producto;

class ProduccionRepository implements RepositoryInterface
{
  public function all(){

  }

  public function create(array  $data){

    $producto =  Producto::findByProCodi($data['producto_final_id']);
    $costoTotal = 0;
    $costoUnitario = 0;
    $produccion = new Produccion;
    $produccion->manId = $produccionId = $produccion->getNextId();
    $produccion->empcodi = empcodi();
    $produccion->manCodi = $data['producto_final_id'];
    $produccion->manCant = $data['producto_final_cantidad'];
    $produccion->manNomb = $producto->ProNomb;
    $produccion->manResp = $data['manResp'];
    $produccion->manDeta = $data['manDeta'];
    // $produccion->manEsta = Produccion::ESTADO_ASIGNADO;
    $produccion->manEsta = Produccion::ESTADO_PRODUCCION;
    $produccion->manCostTota = $costoTotal;
    $produccion->manCostUnit = $costoUnitario;
    $produccion->manFechEmis = $data['manFechEmis'];
    $produccion->manFechCulm = $data['manFechVenc'];
    $produccion->mesCodi = date('Ym');
    $produccion->USER_ECREA = $equipo = gethostname();
    $produccion->USER_CREA = $user = auth()->user()->usulogi;
    $produccion->save();
    $itemsLen =  count($data['producto_insumo_id']);

    logger($produccionId);

    for ( $i = 0; $i < $itemsLen; $i++ ) {
      $item_id = $data['producto_insumo_id'][$i];
      $item_cant = $data['producto_insumo_cantidad'][$i];
      $dataItem = [];
      $productoItem = Producto::findByProCodi($item_id);
      $costoDetalle = 0;
      $importeItem = 0;
      $dataItem['manId'] = $produccionId;
      $dataItem['mandetCodi'] = $item_id;
      $dataItem['mandetNomb'] = $productoItem->ProNomb;
      $dataItem['mandetCant'] = $item_cant;
      $dataItem['mandetCost'] = $costoDetalle;
      $dataItem['mandetImpo'] = $importeItem;
      $dataItem['USER_CREA'] = $user;
      $dataItem['USER_ECREA'] = $equipo;
      ProduccionDetalle::create($dataItem);
    }

    return $produccion;
  }

  public function update(array $data, $id){

    $produccion = Produccion::find($id);

    $producto =  Producto::findByProCodi($data['producto_final_id']);
    $costoTotal = 0;
    $costoUnitario = 0;
    $produccion->manCodi = $data['producto_final_id'];
    $produccion->manCant = $data['producto_final_cantidad'];
    $produccion->manNomb = $producto->ProNomb;
    $produccion->manResp = $data['manResp'];
    $produccion->manDeta = $data['manDeta'];
    $produccion->manCostTota = $costoTotal;
    $produccion->manCostUnit = $costoUnitario;
    $produccion->manFechEmis = $data['manFechEmis'];
    $produccion->manFechVenc = $data['manFechVenc'];
    $produccion->USER_EMODI = $equipo = gethostname();
    $produccion->USER_MODI = $user = auth()->user()->usulogi;
    $produccion->save();


    foreach($produccion->items as $item ){
      $item->delete();
    }

    $itemsLen =  count($data['producto_insumo_id']);
    for ($i = 0; $i < $itemsLen; $i++) {
      $item_id = $data['producto_insumo_id'][$i];
      $item_cant = $data['producto_insumo_cantidad'][$i];
      $dataItem = [];
      $productoItem = Producto::findByProCodi($item_id);
      $costoDetalle = 0;
      $importeItem = 0;
      $dataItem['manId'] = $produccion->manId;
      $dataItem['mandetCodi'] = $item_id;
      $dataItem['mandetNomb'] = $productoItem->ProNomb;
      $dataItem['mandetCant'] = $item_cant;
      $dataItem['mandetCost'] = $costoDetalle;
      $dataItem['mandetImpo'] = $importeItem;
      $dataItem['USER_MODI'] = $user;
      $dataItem['USER_EMODI'] = $equipo;
      $produccion->items()->create($dataItem);
  }

  return $produccion;
}

  public function delete($id){

    $produccion = Produccion::find($id);

    foreach ($produccion->items as $item) {
      $item->delete();
    }

    $produccion->delete();
  }

  public function find($id){
  }
}
