<?php

namespace App\Http\Controllers\Import;

use App\ClienteProveedor;
use App\Http\Controllers\Controller;
use App\Producto;
use App\Venta;

class ImportController extends Controller
{  
  public $model;

  public function __construct($model)
  {
    $this->model = $model;
  }

  public function getModelName()
  {
    if( $this->model instanceof Venta ){
      return 'ventas';
    }
    if ($this->model instanceof ClienteProveedor) {
      return 'clientes';
    }
    if ($this->model instanceof Producto ) {
      return 'productos';
    }        
  }

  public function getData()
  {
    $data = [
      'ventas' => [
        'titulo' => 'Importar Ventas',
        'plantilla' => route('importar.plantilla', 'ventas'),
        'route' => route('importar.ventas.store'),
      ],
      'productos' => [
        'titulo' => 'Importar Productos',
        'plantilla' => route('importar.plantilla', 'productos'),
        'route' => route('importar.productos.store'),
      ],
      'clientes' => [
        'titulo' => 'Importar Clientes',
        'plantilla' => route('importar.plantilla', 'clientes'),
        'route' => route('importar.clientes.store'),
      ],
    ];

    return $data[ $this->getModelName() ];
  }

  public function create()
  {
    return view('importacion.create', $this->getData() );
  }

  public function getPlantilla( $tipo )
  {
    ob_end_clean();

    $name = "sainfo-plantilla-{$tipo}.xlsx";

    $path = public_path(file_build_path('static', 'excell', $name ));
    
    // _dd( $path );
    // exit();
    return response()->download( $path, $name );
  } 
}
