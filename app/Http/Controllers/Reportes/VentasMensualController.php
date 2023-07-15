<?php

namespace App\Http\Controllers\Reportes;

use App\Models\Cierre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VentasMensualController extends Controller
{
  public function show()
  {
    return view('reportes.ventas_mensual.form_new');
  }

  public function getData( Request $request )
  {

    $isFecha = $request->input('tipo') == "fecha";
    $rules = [];
    if( $isFecha ){
      $rules['fecha_desde'] = 'required|date';
      $rules['fecha_hasta'] = 'required|date|after_or_equal:fecha_hasta';
    }

    $this->validate($request, $rules);

    $data = $isFecha ? [] : Cierre::getStadistics($request->mes, true);

    // dd( $data );
    // exit();

    return view('reportes.ventas_mensual.partials.data_complete', compact('data'));
  }
}
