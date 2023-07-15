<?php

namespace App\Listeners;

use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Util\Import\Excell\Producto\ValidateTomaInventarioExcellImport;


class ProductoTomaInventarioExcellListener
{
  public $data;
  public $allProducts;

  public function __construct()
  {
  }

  public function validate()
  {
    $validator = new ValidateTomaInventarioExcellImport($this->data);
    
    $result = $validator
    ->handle()
    ->getResult();
    
    $this->allProducts = $validator->rulesItems->allProducts;
    
    if (!$result->success) {
      throw new Exception( implode('|', $result->errors), 1);
    }
  }

  public function handle($event)
  {
    $this->data = Excel::load($event->request->excell->getRealPath())->get();

    $this->validate();
    
    $almacen = "stock_" . substr($event->request->local,-1);

    foreach ($this->data as $item) {

      $producto = $this->allProducts->where('ProCodi', $item->codigo)->first();
      $productoId = $producto->ID;
      $productoMarca = '-';
      $productoNombre = $producto->ProNomb;
      $productoUnidad = $producto->unidad;
      $stockActual = $almacen;
      $productoStock = $producto->{$stockActual};
      $productoCosto = $producto->ProPUCS;
      
      $data['Id'] = $productoId;
      $data['ProCodi'] = $item->codigo;
      $data['ProMarc'] = $productoMarca;
      $data['proNomb'] = $productoNombre;
      $data['UnpCodi'] = $productoUnidad;
      $data['ProStock'] = $productoStock;
      $data['ProInve'] = $item->stock;
      $data['ProPUCS'] = $productoCosto;

      $event->tomaInventario->detalles()->create($data);
    }

    if ($event->tomaInventario->isCerrada()) {
      $event->tomaInventario->createGuias();
    }
  }
}
