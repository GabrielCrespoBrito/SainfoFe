<?php

namespace App\Http\Controllers\Reportes;

use App\Grupo;
use App\Local;
use App\Marca;
use Illuminate\Http\Request;
use App\Jobs\ReporteKardexFisico2;
use App\Jobs\ReporteProductoStock;
use App\Http\Controllers\Controller;
use App\PDFPlantilla;
use App\Util\PDFGenerator\PDFGenerator;
use App\Util\ExcellGenerator\KardexFisicoExcell;
use App\Util\ExcellGenerator\ProductoStockExcell;

class ProductoStockReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_PRODUCTOSTOCK', 'R_REPORTE'));
  }

  public function create()
  {
    return view('reportes.productos_stock.create');
  }

  public function report(Request $request)
  {
    $reporter = new  ReporteProductoStock($request->all());
    $data = $reporter->getData();

    if (!count($data)) {
      noti()->error('No se han encontrado registros');
      return back();
    }

    $empresa = get_empresa();
    $loccodi = $request->LocCodi;
    $grucodi = $request->GruCodi;
    $famcodi = $request->FamCodi;
    $marcodi = $request->MarCodi;
    $stockMinimo = (bool) $request->input('con_stock_minimo', false);

    $nombreLocal =  $loccodi == "todos" ? 'TODOS' : Local::find($loccodi)->LocNomb;

    if ($grucodi == "todos") {
      $nombreGrupo = 'TODOS';
      $nombreFamilia = 'TODOS';
    } else {
      $grupo = $grupo = Grupo::find($grucodi);
      $nombreGrupo = $grupo->GruNomb;
      $nombreFamilia = $grupo->fams->where('famCodi', $famcodi)->first()->famNomb;
    }

    $nombreMarca = $marcodi == "todos" ? 'TODOS' :  Marca::find($marcodi)->MarNomb;

    $info = [
      'nombre_empresa' => $empresa->nombre(),
      'ruc_empresa' => $empresa->ruc(),
      'nombreLocal' => $nombreLocal,
      'nombreGrupo' => $nombreGrupo,
      'nombreFamilia' => $nombreFamilia,
      'nombreMarca' => $nombreMarca,
      'stockMinimo' => $stockMinimo,
      'data' => $data
    ];


    if ($request->tipo_reporte == "pdf") {
      $view = view('reportes.productos_stock.pdf', $info);
      $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $generator->generator->setGlobalOptions(PDFGenerator::getSetting(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR));
      $generator->generate();
    } else {
      ob_end_clean();
      $excellExport = new ProductoStockExcell($info, 'Productos Stock`');
      $info = $excellExport
        ->generate()
        ->store();
      return response()->download($info['full'],  $info['file']);
    }
  }
}
