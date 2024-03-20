<?php

namespace App\Http\Controllers\Reportes;

use App\Mes;
use Carbon\Carbon;
use App\Models\Cierre;
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Pdf;
use App\Http\Controllers\Controller;
use App\Jobs\Venta\ConsultDocs;
use App\Jobs\Venta\VentaContableReport;
use App\Util\ExcellGenerator\VentaContableExcell;

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