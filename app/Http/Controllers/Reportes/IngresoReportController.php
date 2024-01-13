<?php

namespace App\Http\Controllers\Reportes;


class IngresoReportController extends IngresoEgresoReportController
{
  public $route = "reportes.ingresos.report";
  public $title = "Ingresos";
  public $isIngreso = true;
  
  public function __construct()
  {
    $this->middleware(p_midd('A_INGRESOS', 'R_REPORTE'));
  }
}