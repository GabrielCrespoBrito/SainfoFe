<?php

namespace App\Http\Controllers\Reportes;

use App\Grupo;
use App\Local;
use App\Marca;
use App\PDFPlantilla;
use Illuminate\Http\Request;
use App\Jobs\ReporteIngresoEgreso;
use App\Http\Controllers\Controller;
use App\Jobs\ReporteProductoStockMin;
use App\Util\ExcellGenerator\IngresoEgresoExcell;
use App\Util\PDFGenerator\PDFGenerator;
use App\Util\ExcellGenerator\ProductoStockExcell;


class IngresoEgresoReportController extends Controller
{
  public $title, $route, $isIngreso;

  public function create()
  {
    $empresa = get_empresa();
    $motivos = $this->isIngreso ? $empresa->motivosIngresos : $empresa->motivosEgresos;
    $motivoCampoId = $this->isIngreso ? 'IngCodi' : 'EgrCodi';
    $motivoCampoNombre = $this->isIngreso ? 'IngNomb' : 'Egrnomb';
    $tipoMovimientos = $this->isIngreso ? null : $empresa->getTipoMovimientosEgresos();
    $usuarios = $empresa->users;

    return view('reportes.ingresos_egresos.create', [
      'title' => 'Reporte ' .  $this->title,
      'route' => route($this->route),
      'tipoMovimientos' => $tipoMovimientos,
      'isIngreso' => $this->isIngreso,
      'isEgreso' => !$this->isIngreso,
      'motivos' => $motivos->pluck( $motivoCampoNombre, $motivoCampoId ),
      'usuarios' => $usuarios->pluck('usulogi', 'usucodi'),
    ]);
  }

  public function report(Request $request)
  {

    $isIngreso = (bool) $request->input('is_ingreso');

    $reporter = new ReporteIngresoEgreso($request->all(),  $isIngreso );
    $data = $reporter->getData();

    $titulo = $isIngreso ? 'INGRESOS EN DETALLE' : 'GASTOS EN DETALLE';

    $empresa = get_empresa();

    $info = [
      'nombre_empresa' => $empresa->nombre(),
      'ruc_empresa' => $empresa->ruc(),
      'titulo' => $titulo,
      'fechaDesde' => $request->input('fecha_desde'),
      'fechaHasta' => $request->input('fecha_hasta'),
      'data' => $data
    ];

    if ($request->tipo_reporte == "pdf") {
      $view = view('reportes.ingresos_egresos.pdf', $info);
      $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $generator->generator->setGlobalOptions(PDFGenerator::getSetting(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR));
      $generator->generate();
    } else {
      ob_end_clean();
      $excellExport = new IngresoEgresoExcell($info,  $titulo );
      $info = $excellExport
        ->generate()
        ->store();
      return response()->download($info['full'],  $info['file']);
    }
  }
}
