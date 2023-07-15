<?php

namespace App\Http\Controllers\Reportes;

use App\Local;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\ReporteKardexFecha;
use App\Jobs\Reporte\KardexTrasladoReport;
use App\Jobs\Reporte\ReporteKardexTraslado;
use App\Util\ExcellGenerator\KardexFechaExcell;
use App\Util\ExcellGenerator\KardexTrasladoExcell;
use Symfony\Component\Finder\Iterator\PathFilterIterator;

class KardexTrasladoController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_KARDEXTRASLADO', 'R_REPORTE'))->only(['create']);    
  }

  public function create()
  {

    $almacenes = get_empresa()->almacenes;

    if( $almacenes->count() < 2 ){
      notificacion( 'No se puede generar reporte', 'Necesita al menos 2 locales/almacenes para generar el reporte', 'warning');
      return back();
    }
    
    return view('reportes.kardex_traslado.create', [
      'locales' => $almacenes,
      'fecha_actual' => date('Y-m-d')
    ]);
  }

  public function report( Request $request )
  {
    $this->authorize(p_name('A_KARDEXTRASLADO', 'R_REPORTE'));

    $request->validate([
      'local_origen' => 'required',
      'local_destino' => 'required|different:local_origen',
      'fecha_inicio' => 'required|date',
      'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
      'tipo_reporte' => 'required|in:pdf,excell',
    ]);


    $reporter = new ReporteKardexTraslado($request->fecha_inicio, $request->fecha_final, $request->local_origen, $request->local_destino);
    $reporter->handle();
    $data = $reporter->getData();

    if( ! count($data) ){
      return back()->withErrors([
        'no_regitros' => 'No hubo resultados segun los parametros de busqueda'
      ]);
    }

    ob_end_clean();

    $empresa = get_empresa();
    $nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
    $title = "Reporte de traslado de Almacen";

    $local_origen = Local::find($request->local_origen);
    $local_destino = Local::find($request->local_destino);

    if ($request->tipo_reporte === "excell") {
      // ----------------------------
      $excellExport = new KardexTrasladoExcell(
        $data,
        $title,
        $nombreEmpresa,
        $request->fecha_inicio,
        $request->fecha_final,
        $local_origen,
        $local_destino );

      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    }

    else if ( $request->tipo_reporte === 'pdf' ) {

      $pdfGenerator = new PDFGenerator(view('reportes.kardex_traslado.pdf', [
        'title' => $title,
        'data' => $data,
        'nombre'=> $nombreEmpresa,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_final'  => $request->fecha_final,
        'ruc'          => $empresa->ruc(),
        'razon_social' => $empresa->EmpNomb,
        'local_origen' => $local_origen,
        'local_destino'=> $local_destino,
        'fecha_reporte'=> $request->fecha_inicio . ' - ' . $request->fecha_final        
      ]), PDFGenerator::HTMLGENERATOR );

      $pdfGenerator->generator->setGlobalOptions([
        'no-outline',
        'page-size' => 'Letter',
        'orientation' => 'portrait',
      ]);

      $nameFile = 'reporte_traslado.pdf';
      $pathTemp = getTempPath($nameFile);

      $pdfGenerator->save($pathTemp);
      return response()->download( $pathTemp, 'reporte_traslado.pdf');

    }

  }  
}