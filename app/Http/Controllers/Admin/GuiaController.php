<?php

namespace App\Http\Controllers\Admin;

use App\Sunat;
use App\Empresa;
use App\GuiaSalida;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use App\Admin\SystemStat\SystemStat;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GuiaSalidaController;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class GuiaController extends Controller
{
  public function index(Request $request)
  {
    (new SystemStat)->repository()->clearCache();

    $empresas = Empresa::formatList( session()->get('empresa_id') );
    $estados = StatusCode::getCodesPrincipales();
    $tipos = TipoDocumentoPago::getTiposVentas();
    $title = 'Guias';
    $estado_sunat = $request->input('estado_sunat', null);
    $fecha_inicio = null;
    $fecha_final = date('Y-m-d');
    $routeTableSearch = route('admin.guias.search');

    return view('admin.guias.index', compact('empresas', 'estados', 'tipos', 'title', 'estado_sunat', 'fecha_inicio', 'fecha_final', 'routeTableSearch'));
  }

  public function pending(Request $request)
  {
    (new SystemStat)->repository()->clearCache();

    $empresas_pendientes = (new SystemStat)->getEmpresasGuiasPendientes();

    $empresas = Empresa::formatListPendientes($empresas_pendientes->data, $request->input('empresa', null));
    $routeTableSearch = route('admin.guias.search_pendientes');
    $title = 'Guias Pendientes';
    $routeTableSearch = route('admin.guias.search_pendientes');
    $hasPendientes = $empresas_pendientes->data->result;

    return view('admin.guias.pending', compact('empresas', 'title', 'empresas_pendientes', 'routeTableSearch', 'hasPendientes'));
  }


  public function searchDocs(
    $term,
    $empresa_id,
    $local_id = null,
    $estado_sunat = null,
    $fecha_desde = null,
    $fecha_hasta = null,
    $tipo_guia = null,
    $formato_guia = null  ){

    $empresa = Empresa::find($empresa_id);
    
    (new ActiveEmpresaTenant($empresa))->handle();

    $tipoEntidad =  $tipo_guia == GuiaSalida::INGRESO ? 'P' : 'C';

    $busqueda = (new GuiaSalida)
      ->with(['cli' => function ($query) use ($tipoEntidad) {
        $query->where('TipCodi', $tipoEntidad);
      }, 'almacen'])      
      // Formato
      ->formato($formato_guia)
      // Tipo de guia
      ->tipo($tipo_guia);

    // Estado Sunat
    if ($estado_sunat) {
      $busqueda->whereIn('fe_rpta', (array) $estado_sunat );
    }

    // Local
    if ($local_id) {
      $busqueda->where('Loccodi', '=', $local_id);
    }    

    // // Fechas
    if ($fecha_desde) {
      $busqueda->whereBetween('GuiFemi', [ $fecha_desde, $fecha_hasta ]);
    }

    if( $tipo_guia == GuiaSalida::SALIDA  ){
      $busqueda->orderBy('GuiOper', 'desc');
    }
    else {
      $formato_guia == GuiaSalida::CON_FORMATO ? 
        $busqueda->orderBy('GuiNumee', 'desc') :
        $busqueda->orderBy('GuiOper', 'desc');
    }

    return datatables()->of($busqueda)
      ->addColumn('nrodocumento', 'guia_remision.partials.nrodocumento')
      ->addColumn('estado', 'guia_remision.partials.column_estado')
      ->addColumn('accion', 'admin.partials.column_action')
      ->rawColumns(['accion', 'estado', 'nrodocumento'])
      ->make(true);
  }


  public function search(Request $request)
  {
    return $this->searchDocs(
      $request->input('search')['value'],
      $request->input('empresa_id', null),
      $request->input('local_id', null),
      $request->input('estado_sunat', null),
      $request->input('fecha_desde'),
      $request->input('fecha_hasta'),
      $request->input('tipo_guia'),
      $request->input('formato_guia')
    );
  }


  public function searchPending(Request $request)
  {
    return $this->searchDocs(
      null,
      $request->input('empresa_id', null),
      $request->input('local_id', null),
      [GuiaSalida::ESTADO_SUNAT_PENDIENTE, 98 , 99 ],
      null,
      null,
      GuiaSalida::SALIDA,
      GuiaSalida::CON_FORMATO
    );
  }

  public function sendPending(Request $request)
  {
    (new ActiveEmpresaTenant(Empresa::find($request->empresa_id)))->handle();

    $guia = GuiaSalida::find($request->id_factura);
    $res = $guia->sendApi();
    // $data = Sunat::sendGuia($guia->GuiOper);
    // if ($data['status']) {
    //   return GuiaSalidaController::guiaSuccessMake($guia, $data);
    // } else if ($data['status'] == 0 && $data['code'] == 4000) {
    //   $guia->saveSuccess();
    // }
    // return response()->json(['message' =>  $data['message']], $data['code_http']);


    return response()->json(['message' => $res->data], $res->success ? 200 : 400);

  }


  /**
   * Cambiar la fecha de un documento
   * 
   */
  public function deletePdf(Request $request, $documento_id, $create = "0")
  {
    $empresa = Empresa::find($request->empresa_id);
    (new ActiveEmpresaTenant($empresa))->handle();
    GuiaSalida::find($documento_id)->deletePdf($create, $empresa->ruc());

    return response()->json([
      'success' => true,
    ], 200);
  }

  /**
   * Cambiar la fecha de un documento
   * 
   */
  public function updateFecha(Request $request, $documento_id)
  {
    $empresa = Empresa::find($request->empresa_id);
    (new ActiveEmpresaTenant($empresa))->handle();
    
    $guia = GuiaSalida::find($documento_id);
    
    if( $guia->hasFormato() == false || $guia->isSalida() == false ) {

      $message = $guia->pendiente() ? 'Esta Guia Falta Despacho' : 'Esta Guia No se puede Cambiar de Fecha';

      return response()->json([
        'success' => false,
        'message' => $message
      ], 500);
    }

    $guia->GuiFemi = hoy();
    $guia->GuiFDes = hoy();
    $guia->PanAno = date('Y');
    $guia->PanPeri = date('m');
    $guia->mescodi = date('Ym');
    $guia->save();

    return response()->json([
      'success' => true,
    ], 200);
  }

  /**
   * Cambiar la fecha de un documento
   * 
   */
  public function validarDoc(Request $request, $documento_id)
  {
    $empresa = Empresa::find($request->empresa_id);
    (new ActiveEmpresaTenant($empresa))->handle();

    $guia = GuiaSalida::find($documento_id);

    if ($guia->hasFormato() == false || $guia->isSalida() == false) {

      $message = $guia->pendiente() ? 'Esta Guia Falta Despacho' : 'Esta Guia No se puede Cambiar de Fecha';

      return response()->json([
        'success' => false,
        'message' => $message
      ], 500);
    }

    $guia->GuiFemi = hoy();
    $guia->GuiFDes = hoy();
    $guia->PanAno = date('Y');
    $guia->PanPeri = date('m');
    $guia->mescodi = date('Ym');
    $guia->save();

    return response()->json([
      'success' => true,
    ], 200);
  }

  
}