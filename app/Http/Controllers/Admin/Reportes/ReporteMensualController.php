<?php

namespace App\Http\Controllers\Admin\Reportes;

use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reportes\VentasMensualAbstractController;

class ReporteMensualController extends Controller
{
  use VentasMensualAbstractController;

  public function show($id)
  {
    $routeData = route('admin.reportes.ventas_mensual_getdata', ['id' => $id]);
    $routeDate = route('admin.reportes.consult_date', ['id' => $id]);

    return view('admin.reportes.ventas_mensual', compact('routeData', 'routeDate'));
  }


  public function getData(Request $request, $id)
  {
    empresa_bd_tenant($id);
    // dd("loquesea");
    // exit();
    $routeReporte = route('admin.reportes.ventas_mensual_pdf', ['id' => $id]);
    $routeVentaConsulta = route('admin.documentos.search');
    // $routeVentaConsulta
    return $this->getDataHtml($request, $routeReporte, $routeVentaConsulta, $id);
  }

  public function report(Request $request, $id)
  {
    $empresa = Empresa::find($id);

    empresa_bd_tenant($id);

    return $this->getReport($request, $empresa);
  }

  public function consultDate(Request $request, $id)
  {
    empresa_bd_tenant($id);

    return $this->getConsultDate($request);
  }

}
