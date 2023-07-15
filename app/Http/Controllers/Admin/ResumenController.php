<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use App\Resumen;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use App\Admin\SystemStat\SystemStat;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\Http\Controllers\GuiaSalidaController;
use App\Http\Controllers\SunatController;
use App\Http\Requests\Admin\ValidateResumenRequest;
use App\M;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class ResumenController extends Controller
{
  public function index(Request $request)
  {
    (new SystemStat)->repository()->clearCache();
    
    $empresas = Empresa::formatList($request->input('empresa_id', null));
    $estados = StatusCode::getCodesPrincipales();
    $tipos = TipoDocumentoPago::getTiposVentas();
    $title = 'Resumenes';
    $estado_sunat = $request->input('estado_sunat', null);
    $fecha_inicio = null;
    $fecha_final = date('Y-m-d');
    $routeTableSearch = route('admin.resumenes.search');

    return view('admin.resumenes.index', compact('empresas', 'estados', 'tipos', 'title', 'estado_sunat', 'fecha_inicio', 'fecha_final', 'routeTableSearch'));
  }

  public function pending(Request $request)
  {
    (new SystemStat)->repository()->clearCache();

    $empresas_pendientes = (new SystemStat)->getEmpresasResumenesPendientes();

    $empresas = Empresa::formatListPendientes($empresas_pendientes->data, $request->input('empresa', null));
    $routeTableSearch = route('admin.resumenes.search_pendientes');
    $title = 'Resumenes Pendientes';
    $routeTableSearch = route('admin.resumenes.search_pendientes');
    $hasPendientes = $empresas_pendientes->data->result;

    return view('admin.resumenes.pending', compact('empresas', 'title', 'empresas_pendientes', 'routeTableSearch', 'hasPendientes'));
  }


  public function searchDocs(
    $empresa_id,
    $local_id = null,
    $estado_sunat = null,
    $fecha_desde = null,
    $fecha_hasta = null,
    $tipo_resumen = null, 
    $pendiente = null ) {

    empresa_bd_tenant($empresa_id);

    $busqueda =   Resumen::OrderByDesc('DocNume');

    # Estado Sunat
    if ($tipo_resumen) {
      $busqueda->where('DocMotivo', '=', $tipo_resumen);
    }

    # Estado Sunat
    if ($estado_sunat) {
      $busqueda->where('DocCEsta', '=', $estado_sunat);
    }

    # Local
    if ($local_id) {
      $busqueda->where('LocCodi', '=', $local_id);
    }

    # Fechas
    if ($fecha_desde) {
      // $busqueda->whereBetween('GuiFemi', [$fecha_desde, $fecha_hasta]);
    }

    # Pendiente
    if ($pendiente) {
      $busqueda->where('UDelete',  Resumen::POR_PROCESAR_STATE);
    }

    $busqueda->orderByDesc('DocFechaE');

    return datatables()->of($busqueda)
      ->addColumn('btn', 'admin.partials.column_action')
      ->addColumn('accion', 'admin.resumenes.partials.column_estado')
      ->rawColumns(['accion' , 'btn'])
      ->make(true);
  }


  public function search(Request $request)
  {
    return $this->searchDocs(
      $request->input('empresa_id', null),
      $request->input('local_id', null),
      $request->input('estado_sunat', null),
      $request->input('fecha_desde', null),
      $request->input('fecha_hasta', null),
      $request->input('tipo_resumen', null)
    );
  }


  public function searchPending(Request $request)
  {
    return $this->searchDocs(
      $request->input('empresa_id', null),
      $request->input('local_id', null),
      null,
      null,
      null,
      null,
      1
    );
  }

  public function sendPending(Request $request)
  {
    empresa_bd_tenant($request->empresa_id);
    return (new SunatController)->processResumen( $request->id_factura, $request->docnume );
  }


  /**
   * Validar resumen
   * 
   * @return mixed
   */
  public function validar( ValidateResumenRequest $request, $numoper, $docnume)
  {
    empresa_bd_tenant($request->empresa_id);
    $resumen = Resumen::findMultiple($numoper, $docnume);

    // Validación directa
    if( $request->tipo_validacion == "directo" ){
      $resumen->saveSuccessValidacionByEstadoDocumento();
      return response()->json(['message' => 'Resumen Validado Satisfactoriamente']);
    }
        
    // Validacion por Estado del documento asociado
    else {
      $data = $resumen->validatePorEstado();
      $message = $data->success ? 
        'Resumen Validado Correctamente' : 
        "No se ha podido Validar El Resumen Correctamente, porque el documento {$data->documento->VtaUni} se encuentra en estado {$data->status_documento}";
        $code = $data->success ? 200 : 400;
      return response()->json(['message' => $message], $code);
    }    
  }  

}