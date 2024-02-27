<?php

namespace App\Http\Controllers\Admin;

use App\Venta;
use App\Empresa;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use App\Helpers\DocumentHelper;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Admin\SystemStat\SystemStat;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\Http\Controllers\SunatController;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Http\Requests\Admin\changeDateVentaRequest;
use App\Http\Requests\Admin\DocumentoDeleteRequest;

class DocumentoController extends Controller
{
  public function index(Request $request)
  {  
    (new SystemStat)->repository()->clearCache();
    $empresas = Empresa::formatList( $request->input('empresa_id', null) );
    $estados = StatusCode::getCodesPrincipales();
    $tipos = TipoDocumentoPago::getTiposVentas();
    $title = 'Documentos';
    $estado_sunat = $request->input('estado_sunat', null);
    $fecha_inicio = null;
    $fecha_final = date('Y-m-d');
    $routeTableSearch = route('admin.documentos.search');

    // $routeTableSearch
    return view('admin.documentos.index',compact('empresas', 'estados', 'tipos', 'title', 'estado_sunat', 'fecha_inicio', 'fecha_final', 'routeTableSearch'));
  }

  public function pending(Request $request)
  {
    (new SystemStat)->repository()->clearCache();

    $empresasAll = $request->input('empresasAll', false);
    $title = 'Documentos Pendientes';
    
    $routeTableSearch = route('admin.documentos.search_pendientes');
    if ( $empresasAll ) {
      $empresas_pendientes = [];
      $empresas = Empresa::formatListPendientes();
      $hasPendientes = true;
    } 
    
    else {
      $empresas_pendientes = (new SystemStat)->getEmpresasVentasPendientes();
      $empresas = Empresa::formatListPendientes($empresas_pendientes->data);
      $hasPendientes = $empresas_pendientes->data->result;
    }


    return view('admin.documentos.pending', compact('empresas', 'title', 'empresas_pendientes','routeTableSearch', 'hasPendientes', 'empresasAll'));
  }


  public function searchDocs( $term, $empresa_id, $local_id = null, $estado_sunat = null, $fecha_desde = null, $fecha_hasta = null, $tipo_documento = null, $estado_almacen = null  )
  {
    $empresa = Empresa::find($empresa_id);

    (new ActiveEmpresaTenant($empresa))->handle();

    $busqueda = DB::connection('tenant')->table('ventas_cab')
    ->join('prov_clientes', function ($join) {
      $join
        ->on('prov_clientes.PCCodi', '=', 'ventas_cab.PCCodi')
        ->on('prov_clientes.EmpCodi', '=', 'ventas_cab.EmpCodi')
        ->where('prov_clientes.TipCodi', '=', 'C');
    })
      ->join('local', function ($join) {
        $join
          ->on('local.LocCodi', '=', 'ventas_cab.LocCodi')
          ->on('local.EmpCodi', '=', 'ventas_cab.EmpCodi');
      })
      ->join('moneda', 'moneda.moncodi', '=', 'ventas_cab.MonCodi')
      ->select(
        'ventas_cab.VtaOper',
        'ventas_cab.TidCodi',
        'ventas_cab.VtaNume',
        'ventas_cab.VtaCant',
        'ventas_cab.VtaNumee',
        'ventas_cab.LocCodi',
        'ventas_cab.VtaSeri',
        'ventas_cab.VtaImpo',
        'ventas_cab.VtaPago',
        'ventas_cab.VtaEsta',
        'ventas_cab.VtaSald',
        'ventas_cab.AlmEsta',
        'ventas_cab.VtaXML',
        'ventas_cab.VtaSdCa',
        'ventas_cab.VtaCDR',
        'ventas_cab.VtaPDF',
        'ventas_cab.fe_rpta',
        'ventas_cab.VtaFMail',
        'ventas_cab.fe_obse',
        'ventas_cab.VtaFvta',
        'prov_clientes.PCNomb',
        'moneda.monabre'
      );

    # Local
    if ( $local_id ) {
      $busqueda->where('ventas_cab.LocCodi', '=',  $local_id );
    }

    # Consultar Estatus
    if ($estado_sunat ) {
      $busqueda->where('ventas_cab.VtaFMail', '=', $estado_sunat );
    }

    # Consultar por Fecha
    if ( $fecha_desde ) {
      $busqueda->whereBetween('ventas_cab.VtaFvta', [ $fecha_desde, $fecha_hasta ]);
    }

    # Consultar Tipo de Documento
    if ( $tipo_documento ) {
      $busqueda->where('ventas_cab.TidCodi', '=' , $tipo_documento );
    }

    # Estado Almacen
    if ( $estado_almacen ) {
      $filter = $estado_almacen == "Pe" ? '>' : '=';
      $busqueda->where('ventas_cab.VtaSdCa', $filter, 0);
    }

    $busqueda->orderByDesc('ventas_cab.VtaFvta');

    # Buscar
    if ( $term  ) {
      $busqueda = $busqueda
        ->where('prov_clientes.PCNomb', 'LIKE', '%' . $term . '%')
        ->orWhere('ventas_cab.VtaNume', 'LIKE', '%' . $term . '%')
        ->get();
    }

    $dataTable = DataTables::of($busqueda)
      ->addColumn('estado', 'admin.documentos.partials.column_estado')
      ->addColumn('accion', 'admin.partials.column_action')
      ->addColumn('alm', 'partials.column_alm')
      ->rawColumns(['nro_venta', 'accion', 'estado', 'btn', 'alm'])
      ->make(true);

    return $dataTable;
  }


  public function search(Request $request)
  {
    return $this->searchDocs(
      $request->input('search')['value'],
      $request->input('empresa_id', null),
      $request->input('local_id', null),
      $request->input('estado_sunat', null) ?? $request->input('status', null),
      $request->input('fecha_desde'),
      $request->input('fecha_hasta'),
      $request->input('tipo_documento') ?? $request->input('tipo'),
      $request->input('estado_almacen')
    );
  }


  public function searchPending(Request $request)
  {
    return $this->searchDocs(
      null,
      $request->input('empresa_id', null),
      $request->input('local_id', null),
      StatusCode::CODE_ERROR_0011
    );
  }

  public function sendPending(Request $request)
  {
    (new ActiveEmpresaTenant(Empresa::find($request->empresa_id)))->handle();
    
    $documento_id = $request->id_factura;

    $venta = Venta::find($documento_id);

    if((new DocumentHelper())->enPlazoDeEnvio( $venta->TidCodi, $venta->VtaFvta) ) {
      return response()->json(['message' => 'Documento Fuera de Plazo'], 400);
    }

    return (new SunatController())->sendSunatVenta($documento_id , true);
  }

  /**
   * Cambiar la fecha de un documento
   *
   */
  public function changeDate(changeDateVentaRequest $request , $documento_id)
  {
    (new ActiveEmpresaTenant(Empresa::find($request->empresa_id)))->handle();

    $date = get_date_info($request->date);
    Venta::find($documento_id)->update([
      'VtaFvta' => $date->full,
      'MesCodi' => $date->mescodi,
      'PanAno' => $date->year,
      'PanPeri' => $date->month,
    ]);    

    return response()->json([
      'new_value' => $request->date,
    ], 200);
  }


  /**
   * Cambiar la fecha de un documento
   * 
   */
  public function delete( Request $request, $documento_id)
  {
    (new ActiveEmpresaTenant(Empresa::find($request->empresa_id)))->handle();

    Venta::find($documento_id)->deleteComplete();

    return response()->json([
      'success' => true,
    ], 200);
  }

  /**
   * Cambiar la fecha de un documento
   * 
   */
  public function deletePdf(Request $request, $documento_id , $recreate = "0")
  {
    $empresa = Empresa::find($request->empresa_id);
    (new ActiveEmpresaTenant($empresa))->handle();
    Venta::find($documento_id)->deletePdf($recreate, $empresa->ruc());

    return response()->json([
      'success' => true,
    ], 200);
  }

  
}