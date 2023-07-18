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

    if($request->input('consult') ){
      ini_set('max_execution_time', '300');
      (new ConsultDocs($request->fecha_desde, $request->fecha_hasta ))->handle();
    }

    $data = $isFecha ? Cierre::getStadisticsByFechas($request->fecha_desde, $request->fecha_hasta) : Cierre::getStadistics($request->mes);

    return view('reportes.ventas_mensual.partials.data_complete', compact('data'));
  }


  public function report(Request $request)
  {
    // dd( $request->all() );
    // exit();

    $empresa = get_empresa();
    $formato = $request->formato;
    $mescodi = $request->mes;
    $estadoSunat = $request->estado_sunat;
    $year = substr($mescodi, 0, 4);
    $mes = substr($mescodi, 4, 6);
    $fecha_inicio = $request->fecha_inicio;
    $fecha_final =  $request->fecha_final;
    // dd($estadoSunat);
    // exit();
    $report = new VentaContableReport( $fecha_inicio, $fecha_final, $request->tipo, $estadoSunat);

    if ($request->cerrar_mes) {
      Cierre::createIfNotExists($mescodi);
    }

    $data = $report
      ->handle()
      ->getData();

    set_time_limit(0);
    ini_set('memory_limit', '3000M'); //This might be too large, but depends on the data set

    // Formato PDF
    if ($formato == "pdf") {

      $pdf = new Pdf([
        'commandOptions' => [
          'useExec' => true,
          'escapeArgs' => false,
          'locale' => 'es_ES.UTF-8',
          'procOptions' => [
            // This will bypass the cmd.exe which seems to be recommended on Windows
            'bypass_shell' => true,
            // Also worth a try if you get unexplainable errors
            'suppress_errors' => true,
          ],
        ],
      ]);

      $globalOptions = ['no-outline', 'page-size' => 'Letter', 'orientation' => 'landscape'];

      $view = view('reportes.ventas_mensual.pdf', [
        'ventas_group' => $data['items'],
        'total' => $data['total'],
        'nombre_empresa' => $empresa->EmpNomb,
        'ruc_empresa' => $empresa->EmpLin1,
        'periodo' => sprintf('%s - %s', $fecha_inicio, $fecha_final),

        // 'periodo' => Mes::find($request->mes)->mesnomb

      ]);

      $pdf->setOptions($globalOptions);
      $pdf->addPage($view);
      $pdf->binary = getBinaryPdf();

      if (!$pdf->send()) {
        throw new \Exception('Could not create PDF: ' . $pdf->getError());
      }
    }

    if ($formato == "html") {

      return view('reportes.ventas_mensual.form', [
        'mes'     => $mescodi,
        'formato' => $formato,
        'estado_sunat' => $estadoSunat,
        'data_reporte'     => [
          'ventas_group' => $data['items'],
          'total' => $data['total'],
          'nombre_empresa' => $empresa->EmpNomb,
          'ruc_empresa' => $empresa->EmpLin1,
          'periodo' => Mes::find($request->mes)->mesnomb
        ]
      ]);
    }

    // excell 
    if ($formato == "excell") {

      ob_end_clean();
      $nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
      $excellExport = new VentaContableExcell($data, Mes::find($request->mes)->mesnomb, $nombreEmpresa);

      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    }
  }

  public function consultDate( Request $request )
  {
    $data =  Cierre::getFechaUpdate($request->data);
 
    return response()->json([
      'date' => $data,
    ]);
  }


}
