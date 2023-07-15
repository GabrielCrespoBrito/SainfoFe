<?php

namespace App\Http\Controllers\Reportes;

use App\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Reporte\ReporteCompraVentaProducto;
use App\Http\Requests\BuscarCompraVentaProductoRequest;

class ReporteCompraVentaController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_COMPRAVENTA','R_REPORTE'))->only('create');
  }


  public function create(Request $request)
  {
    $producto_id = null;
    $producto_nombre = "";
    $fecha_inicio = now()->firstOfMonth()->format('Y-m-d');
    $fecha_final = now()->format('Y-m-d');


    if ($request->producto) {
      $producto = Producto::findByProCodi($request->producto);
      $producto_id = $producto->ProCodi;
      $producto_nombre = $producto->ProNomb;
    }

    return view('reportes.compra_venta', [
      'producto_id' => $producto_id,
      'producto_nombre' => $producto_nombre,
      'fecha_inicio' => $fecha_inicio,
      'fecha_final' => $fecha_final,
    ]);
  }


  public function search(BuscarCompraVentaProductoRequest $request)
  {
    $this->authorize(p_name('A_COMPRAVENTA', 'R_REPORTE'));

    $reporter = new ReporteCompraVentaProducto(
      $request->id_producto,
      $request->condicion == "ventas",
      $request->fecha_desde,
      $request->fecha_hasta
    );

    $reporter->handle();
    $data = $reporter->getData();

    if ($data['success']) {
      return response()->json(['totales' => $data['totals'], 'data_productos' => $data['docs']], 200);
    } else {
      $msg = "No se encontraròn registros";

      return response()->json(['message' => $msg,  'errors' => ['producto' =>  ["No existen {$request->condicion} de este producto, es el lapso de  tiempo seleccionado ({$request->fecha_desde} - {$request->fecha_hasta})"]]], 500);
    }

    return $request->all();
  }
}
