<?php

namespace App\Http\Controllers\Reportes;

class EgresoReportController extends IngresoEgresoReportController
{
  public $route = "reportes.egresos.report";
  public $title = "Egresos";
  public $isIngreso = false;

  public function __construct()
  {
    $this->middleware(p_midd('A_EGRESOS', 'R_REPORTE'));
  }
}