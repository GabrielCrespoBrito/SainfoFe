<?php

namespace App\Http\Controllers;

use App\Local;
use App\Sunat;
use App\Venta;
use App\GuiaSalida;
use App\Models\Cierre;
use App\SerieDocumento;
use App\ClienteProveedor;
use App\TipoDocumentoPago;
use App\VentaConsultaSunat;
use Illuminate\Http\Request;
use App\Jobs\ReporteKardexFisico;
use App\Jobs\ReporteKardexFisico2;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Controllers\Reportes\Reporte;
use App\Http\Controllers\Reportes\ListaPrecio;
use App\Util\ExcellGenerator\KardexFisicoExcell;
use App\Http\Controllers\Reportes\UtilidadesVentas;
use App\Util\ExcellGenerator\KardexValorizadoExcell;
use App\Http\Requests\Kardex\KardexFisicoReporteRequest;
use App\Util\ExcellGenerator\KardexValorizadoResumenExcell;
use App\Http\Controllers\Reportes\KardexValorizado\ReporteKardex;

class ReportesController extends Controller
{

  public function __construct()
  {
    $this->empresa = get_empresa();
    $this->middleware(p_midd('A_REPORTE_MOVIMIENTO', 'R_PRODUCTO'))->only('productoMovimientosReporte');
    $this->middleware(p_midd('A_KARDEXFISICO', 'R_REPORTE'))->only(['kardex_fisico', 'kardex_pdf']);
    $this->middleware(p_midd('A_KARDEXVALORIZADO', 'R_REPORTE'))->only(['kardex_valorizado', 'kardex_valorizado_pdf']);
    $this->middleware(p_midd('A_VENTA', 'R_REPORTE'))->only('ventas');
    $this->middleware(p_midd('A_UTILIDADESVENTAS2', 'R_REPORTE'))->only('utilidades_ventas', 'utilidades_ventas_pdf');
    $this->middleware(p_midd('A_CONSULTARDOCUMENTO', 'R_VENTA'))->only('consultar_documentos');
  }

  public function clientes()
  {
    return view('reportes.clientes.form');
  }


  public function ventas($venta = true)
  {
    $tipos_documentos = SerieDocumento::ultimaSerie();
    $empresa    = get_empresa();
    $almacenes  = $empresa->almacenes;
    $vendedores = $empresa->vendedores;
    $view =  $venta ? 'reportes.ventas' : 'reportes.guias';
    return view($view, compact('almacenes', 'vendedores', 'tipos_documentos'));
  }

  public function importe_mensual(Request $request)
  {
    return response()->json([
        'data' => Cierre::getStadistics($request->date)
    ]);
  }


  public function visualizacion(
    // 
    $tipo_reporte,
    $cliente,
    $local,
    $fecha_desde,
    $fecha_hasta,
    $tipo_documento,
    $serie,
    $vendedor,
    $reporte
  ) {

    $empresa = get_empresa();
    $is_venta = $reporte == "ventas";
    $cliente_select = $cliente != "todos";
    $cliente_info = null;

    if ($cliente_select) {
      $cliente_info = ClienteProveedor::findByRuc($cliente, null, ClienteProveedor::TIPO_CLIENTE);
    }

    $model = $is_venta ?  Venta::with('vendedor')->whereBetween('VtaFvta', [$fecha_desde, $fecha_hasta])->where('EmpCodi', empcodi()) : GuiaSalida::whereBetween('GuiFemi', [$fecha_desde, $fecha_hasta])->where('EmpCodi', empcodi());

    if ($model->count()) {

      if ($cliente_select) {
        $model->where('PCCodi', $cliente_info->PCCodi);
      }

      if ($local != "todos") {
        $field = $is_venta ? 'LocCodi'  : 'Loccodi';
        $model->where($field, $local);
      }


      if ($is_venta) {

        if ($tipo_documento == "todos") {
          // $model->whereNotIn('TidCodi', [ TipoDocumentoPago::COTIZACION, TipoDocumentoPago::PREVENTA, TipoDocumentoPago::ORDEN_PAGO  ]);
        } else {          
          $model->where('TidCodi', $tipo_documento);
        }
      } else {

        if ($tipo_documento != "999") {
          $model->where('fe_rpta', $tipo_documento);
        } else {
          $model->whereNot('fe_rpta', ["0", "9"]);
        }
      }

      if ($is_venta && $serie != "todos") {
        $model->where('VtaSeri', $serie);
      }


      if ($is_venta) {
        if ($vendedor != "todos") {
          $model->where('Vencodi', $vendedor);
        }
      } else {
        if ($vendedor != "todos") {
          $model->where('GuiEFor', $vendedor);
        }
      }

      if ($is_venta) {
        $ventas_groups = $model->get()->groupBy('TidCodi');
      } else {
        $ventas_groups = $model->get()->groupBy('GuiSeri');
      }

      $empresa = $empresa->_toArray('EmpLogo', 'EmpLogo1');


      $view = view('reportes.reporte_pdf', compact('ventas_groups', 'tipo_reporte', 'cliente', 'fecha_desde', 'fecha_hasta', 'tipo_documento', 'serie', 'vendedor', 'local', 'empresa', 'is_venta', 'cliente_select', 'cliente_info'));


      $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $generator->generate();
    }

    return "No se encuentran documentos para el reporte";
  }

  public function consultar_documentos()
  {
    $tipos_documentos = SerieDocumento::ultimaSerie();

    $rango = Venta::searchRangoVtaNume(
      date('Y-m-d'),
      date('Y-m-d'),
      $tipos_documentos->first()['id'],
      $tipos_documentos->first()['series'][0]['id']
    );

    return view('reportes.consultar_documentos', [
      "tipos_documentos" => $tipos_documentos,
      'rangos'  => $rango
    ]);
  }

  public function buscar_rangos(Request $request)
  {
    $rango = Venta::searchRangoVtaNume(
      $request->fecha_desde,
      $request->fecha_hasta,
      $request->tipo_documento,
      $request->serie_documento
    );

    return $rango;
  }



  public function documentos_faltantes(Request $request)
  {
    $empresa = get_empresa();

    $desde = (int) $request->correlativo_desde;
    $hasta = (int) $request->correlativo_hasta;

    $data = [
      'totales' => $hasta - $desde,
      'encontradas' => 0,
      'faltantes' => 0,
      'inexistente' => 0,
      'ventas_faltantes' => []
    ];

    ini_set('max_execution_time', 360);

    for ($i = $desde; $i <= $hasta; $i++) {

      $VtaNume = agregar_ceros($i, 6, 0);
      $venta =
        Venta::where('VtaNumee', $VtaNume)
        ->where('TidCodi', $request->tipo_documento)
        ->where('VtaSeri', $request->serie_documento)
        ->where('Empcodi', $empresa->empcodi)
        ->first();

      if (is_null($venta)) {

        $data['inexistente'] += 1;
        $venta_inexistente = [
          'VtaOper' => $VtaNume,
          'VtaNume' =>  $request->serie_documento . '-' . $VtaNume,
          'VtaFvta' => '-',
          'Estado'  => 0
        ];

        array_push($data["ventas_faltantes"], $venta_inexistente);
      }

      // else 
      else {

        if ($consulta = $venta->consulta) {
          if ($request->reprocesar == "true") {
            $respuesta = Sunat::verificarDocument($venta->VtaOper, true);
            $codigo = $respuesta->statusCdr->statusCode;
            $consulta->updateConsulta($respuesta);
          }

          $codigo = $consulta->CodiSunat;
        } else {
          $respuesta = Sunat::verificarDocument($venta->VtaOper, true);
          $codigo = $respuesta->statusCdr->statusCode;
          VentaConsultaSunat::createRegistro($venta, $respuesta);
        }

        if ((int) $codigo === 127) {
          $data['faltantes'] += 1;
          $venta_faltante  = $venta->toArray();
          $venta_faltante['Estado']  = 1;
          array_push($data["ventas_faltantes"],  $venta_faltante);
        } else {
          $data['encontradas'] += 1;
        }
      } // else 

    } // end for

    return $data;
  }


  public function buscar_sunat()
  {
  }


  function pdf_documentos_faltantes(Request $request)
  {
    $path =  public_path(trim("temp\ ") . str_random(10) . '.pdf');
    $data = $request->all();
    $data['empresa'] = get_empresa();
    $pdf  = \PDF::loadView('reportes.documentos_faltantes_pdf', $data);
    $pdf->save($path);
    $contenido = base64_encode(file_get_contents($path));
    dar_permisos($path);
    $nombre = $data['tipo_documento'] . '_' . $data["serie_documento"] . ".pdf";
    return ['contenido' =>  $contenido, 'nombre' => $nombre];
  }

  public function kardex_fisico()
  {
    $locales = get_empresa()->almacenes;
    return view(
      'reportes.kardex_fisico',
      ['locales' => $locales]
    );
  }


  public function kardex_valorizado()
  {
    $locales = get_empresa()->almacenes;
    return view(
      'reportes.kardex_valorizado',
      [
        'locales' => $locales,
        'localSelected' => auth()->user()->localCurrent()->loccodi,
      ]
    );
  }

  public function kardex_valorizado_pdf(Request $request, $mes, $local, $tipo, $reprocesar = 0, $formato = "pdf")
  {
    $this->authorize(p_name('A_KARDEXVALORIZADO', 'R_REPORTE'));

    ob_end_clean();

    $formato_file = $formato === 'pdf' ? 'pdf' : 'xlsx';

    $fileHelper = FileHelper();
    $ruc = get_ruc();
    $local_str = ($local === "000" || $local === "todos" || $local === null) ? '' : $local;
    $tipo_str = $tipo === "detalle" ? '01' : "02";
    $local_tipo_str = $local_str . $tipo_str;

    // Nombre del Archivo
    $namePDF = sprintf(
      "%s_%s_%s_%s.%s",
      'kardexvalorizado',
      $ruc,
      $mes,
      $local_tipo_str,
      $formato_file
    );

    if (!$reprocesar) {
      $pathTemp = file_build_path('temp', $namePDF);
      if ($fileHelper->pdfExist($namePDF)) {
        \File::put(public_path($pathTemp), $fileHelper->getPdf($namePDF));
        if ($tipo == "pdf") {
          return response()->file($pathTemp, [
            'Content-Description' => 'File Transfer',
            'Content-Type' => "application/pdf",
          ]);
        } else {
          return response()->download($pathTemp, $namePDF);
        }
      }
    }

    $reporter = new ReporteKardex($mes, $local, $tipo == 'detalle');
    $reporter->make();
    $data = $reporter->getInfoReporte();

    // Formato en excell
    if ($formato == "pdf") {
      $viewName = $tipo == "detalle" ? 'reportes.kardex_valorizado_pdf' : 'reportes.kardex_valorizado_resumen_pdf';
      $pdf = new PDFGenerator(view($viewName, $data), PDFGenerator::HTMLGENERATOR);
      $fileHelper->save_pdf($namePDF, $pdf->generator->toString());
      $pdf->generator->setGlobalOptions([
        'page-size' => 'A4',
        'orientation' => 'landscape',
      ]);
      $pdf->generate();
    }

    // Formato en excell
    else {
      $excellExport = $tipo == "detalle" ?
        new KardexValorizadoExcell($data, 'reporte') :
        new KardexValorizadoResumenExcell($data, 'reporte');
      $info = $excellExport
        ->generate()
        ->store();

      $fileHelper->save_pdf($namePDF, file_get_contents($info['full']));

      return response()->download($info['full'], $namePDF);
    }
  }



  public function productoMovimientosReporte(KardexFisicoReporteRequest $request)
  {
    return $this->kardexFisico(
      $request->input('LocCodi', 'todos'),
      $request->fecha_desde,
      $request->fecha_hasta,
      $request->articulo_desde,
      $request->articulo_hasta,
    );
  }


  /**
   * Kardex Fisico
   */
  public function kardexFisico($loccodi, $fecha_desde, $fecha_hasta, $articulo_desde, $articulo_hasta)
  {
    $reporte = new ReporteKardexFisico(
      $loccodi,
      $fecha_desde,
      $fecha_hasta,
      $articulo_desde,
      $articulo_hasta,
    );

    $data = $reporte->getData();


    $empresa = get_empresa();
    // $data = $request->all();
    $local_id = $loccodi;
    $search_local_all = $local_id == "todos";

    $nombre_local =
      $search_local_all ?
      'TODOS' :
      $empresa
      ->almacenes
      ->where('LocCodi', $local_id)
      ->first()
      ->LocNomb;

    if (count($data)) {

      $info = [
        'nombre_empresa' => $empresa->nombre(),
        'ruc_empresa' => $empresa->ruc(),
        'nombre_local' => $nombre_local,
        'fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta,
        'articulo_desde' => $articulo_desde,
        'articulo_hasta' => $articulo_hasta,
        'fecha_anterior' => $reporte->fecha_anterior_al_inicio,
        'LocCodi' => $loccodi,
        'data' => $data
      ];

      $pdf  = \PDF::loadView('reportes.kardex_pdf', $info);
      return $pdf->stream();
    }

    notificacion('', 'No se ha encontrado información', 'error');
    return redirect()->back();
  }


  /**
   * Kardex Fisico
   */
  public function kardex_pdf(KardexFisicoReporteRequest $request)
  {
    $reporter = new ReporteKardexFisico2($request->all());
    $data = $reporter->getData();

    if (!count($data)) {
      noti()->error('No se han encontrado registros');
      return back();
    }

    $empresa = get_empresa();
    $loccodi = $request->LocCodi;
    $nombreLocal =  $loccodi == "todos" ? 'TODOS' : Local::find($loccodi)->LocNomb;
    $info = [
      'nombre_empresa' => $empresa->nombre(),
      'ruc_empresa' => $empresa->ruc(),
      'nombre_local' => $nombreLocal,
      'fecha_desde' => $reporter->fechaInicio,
      'fecha_hasta' => $reporter->fechaFinal,
      'articulo_desde' => $reporter->filterProduct ? $reporter->productoIdDesde : null,
      'articulo_hasta' => $reporter->filterProduct ?  $reporter->productoIdHasta : null ,
      'fecha_anterior' => $reporter->fechaAnteriorInicio,
      'LocCodi' => $loccodi,
      'data' => $data
    ];


    if ($request->tipo_reporte == "pdf") {
      $view = view('reportes.kardex_pdf', $info);
      $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      $generator->generate();
    } 

    else {

      ob_end_clean();
      $excellExport = new KardexFisicoExcell($info, 'KardexFisico');
      $info = $excellExport
        ->generate()
        ->store();
      return response()->download($info['full'],  $info['file']);
    }
  }

  public function utilidades_ventas()
  {
    return view('reportes.utilidades_ventas.form');
  }

  public function utilidades_ventas_pdf(Request $request)
  {
    $is_resumen = $request->tipo_reporte == "resumen";
    $utilidades = new UtilidadesVentas($request->fecha_desde, $request->fecha_hasta, $is_resumen);
    return $utilidades->streampdf();
  }

  # Guias
  public function guias()
  {
    return view('reportes.guias');
  }

  public function guias_pdf(Request $request)
  {
    return $request->all();
  }


  public function listaPrecios()
  {
  }
}
