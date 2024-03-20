<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VentasMensualController extends Controller
{
  use VentasMensualAbstractController;

  public function show()
  {
    $routeData = route('reportes.ventas_mensual_getdata');
    $routeDate = route('reportes.consult_date');

    $view = auth()->user()->isContador() ? 'reportes.ventas_mensual.form_new_contador' : 'reportes.ventas_mensual.form_new';

    return view($view, compact('routeData', 'routeDate'));
  }

  public function getData(Request $request )
  {
    $routeReporte = $routeReporte ?? route('reportes.ventas_mensual_pdf');

    $routeVentaConsulta = route('ventas.consulta');
    return $this->getDataHtml($request, $routeReporte, $routeVentaConsulta, null );
  }

  public function report( Request $request )
  {
    return $this->getReport( $request, get_empresa() );
  }

  public function consultDate(Request $request)
  {
    return $this->getConsultDate($request);
  }
  
}