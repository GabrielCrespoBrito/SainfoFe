<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\ClienteAdministracion\Traits\FileVentas;
use App\Mes;
use App\Models\Cierre;
use Chumper\Zipper\Zipper;
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Pdf;
use App\Jobs\Venta\ConsultDocs;
use App\Http\Controllers\Controller;
use App\Jobs\Venta\VentaContableReport;
use App\Jobs\Reportes\VentaContableSireTxt;
use App\Util\ExcellGenerator\VentaContableExcell;

trait VentasMensualAbstractController
{
  use FileVentas;

  public function getDataHtml(Request $request, $routeReporte, $routeVentaConsulta, $empresa_id = null)
  {
    $isFecha = $request->input('tipo') == "fecha";
    $rules = [];

    $searchSunat = null;

    if ($request->input('consult')) {
      ini_set('max_execution_time', '300');
      (new ConsultDocs($request->fecha_desde, $request->fecha_hasta))->handle();
      $searchSunat = date('Y:m:d H:i:s');
    }

    $data = $isFecha ?
      Cierre::getStadisticsByFechas($request->fecha_desde, $request->fecha_hasta) :
      Cierre::getStadistics($request->mes, $searchSunat);

    $isContador = auth()->user()->isContador();

    return view('reportes.ventas_mensual.partials.data_complete', compact('data', 'isFecha', 'routeReporte', 'routeVentaConsulta', 'empresa_id', 'isContador'));
  }


  public function getReport(Request $request, $empresa)
  {
    $formato = $request->formato;
    $mescodi = $request->mes;
    $estadoSunat = $request->estado_sunat;
    $fecha_inicio = $request->fecha_inicio;
    $fecha_final =  $request->fecha_final;
    $isFileReport = $formato == "archivos";
    // dd($estadoSunat);
    // exit();
    $report = new VentaContableReport($fecha_inicio, $fecha_final, $request->tipo, $estadoSunat);

    if($isFileReport){
      $report->setOnlyGetQuery(true);
    }

    if ($request->cerrar_mes) {
      Cierre::createIfNotExists($mescodi);
    }

    $data = $isFileReport ? 

    $report->handle() :
    
    $report
      ->handle()
      ->getData();

    set_time_limit(0);
    ini_set('memory_limit', '3000M'); 

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
          'periodo' => $fecha_inicio . '-' . $fecha_final
        ]
      ]);
    }

    // Excell
    if ($formato == "excell") {

      ob_end_clean();
      $nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
      $excellExport = new VentaContableExcell($data, $fecha_inicio . '-' . $fecha_final, $nombreEmpresa);

      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    }


    if ($formato == "txt_sire") {
      
      ob_end_clean();

      $dateInfo = get_date_info($request->fecha_inicio);
      $txtSireExport = new VentaContableSireTxt($empresa, $dateInfo->mescodi, $data);
      $txtSireExport->handle();
      
      Cierre::findByMes($dateInfo->mescodi)->cerrar();

      $nameTxt = $txtSireExport->getFileName('.txt');
      // $path = fileHelper()->saveTemp($txtSireExport->getContent(), $fileName);
      $nameZip =  $txtSireExport->getFileName('.zip');
      $pathTempZip = getTempPath($nameZip);

      $zipper = new Zipper;
      $zipper
      ->make($pathTempZip)
      ->addString( $nameTxt,$txtSireExport->getContent())
      ->close();

      return response()->download($pathTempZip, $nameZip );
    }

    // Descargar Archivos
    if( $formato == "archivos" ){

      $comprimido =  $this->saveFiles($data->toArray(), $empresa->empcodi );

      // _dd( $comprimido );
      // exit();

      if ($comprimido) {
        ob_end_clean();

        // $contenido = base64_encode(file_get_contents($comprimido['path']));
        return response()->file($comprimido['path']);

        // return ['contenido' =>  $contenido, 'nombre' =>  $comprimido['name'], 'type' => 'zip'];
      } else {
        return response()->json(['message' => 'No se encontrarón archivos para descargar'], 400);
      }

      
    }


  }

  public function getConsultDate(Request $request)
  {
    $data =  Cierre::getFechaUpdate($request->data);

    return response()->json([
      'date' => $data,
    ]);
  }
}
