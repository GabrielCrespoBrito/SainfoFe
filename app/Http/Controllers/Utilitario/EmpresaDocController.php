<?php

namespace App\Http\Controllers\Utilitario;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\ModuloMonitoreo\Empresa\Empresa;
use App\ModuloMonitoreo\DocSerie\DocSerie;
use App\ModuloMonitoreo\DocStatus\DocStatus;
use App\ModuloMonitoreo\Document\Document;
use App\Util\BuscadorSunat\BuscadorSunatDocument;

class EmpresaDocController extends Controller
{
  public function queryR($id, $serie_id, $input, $mescodi, $estatus = null)
  {
    // $busqueda = \DB::table('monitor_empresa_documentos')
    $busqueda = $busquedaInit = \DB::table('monitor_empresa_documentos')
      ->join('monitor_documentos_status', function ($join) {
        $join->on('monitor_documentos_status.documento_id', '=', 'monitor_empresa_documentos.id');
      })
      ->join( 'monitor_empresa_series' , function ($join) {
        $join->on( 'monitor_empresa_series.id' , '=', 'monitor_empresa_documentos.serie_id' );
      })
      ->join('monitor_status_codes', function ($join) {
        $join->on('monitor_status_codes.id', '=', 'monitor_documentos_status.status_id');
      })
      ->where('monitor_empresa_series.empresa_id', '=', $id);
      // ->where('monitor_empresa_documentos.mescodi', '=', $mescodi);

    // Filtrar por serie
    if ($serie_id) {
      $busqueda->where( 'monitor_empresa_series.id' , '=' , $serie_id );
    }

    // Filtrar por status
    if ($estatus) {
      $busqueda->where('monitor_documentos_status.status_id', '=', $estatus);
    }

    return $busqueda;
  }

  public function search(Request $request , $id )
  {
    $serie_id = $request->input('serie_id');
    $input = trim($request->input('search.value'));
    $mescodi = $request->input('mescodi');
    $status = $request->input('status');

    //     
    $busqueda = $this->queryR($id, $serie_id, $input, $mescodi, $status);
    
    if( $input != null  ){
      $busqueda->where('monitor_empresa_documentos.numero', '=', $input);     
    }

    return DataTables::of($busqueda)
      ->make(true);
  }

  public function showDocs($id = null)
  {
    $empresas_mod = Empresa::all();
    $empr = $id ? Empresa::find($id) : $empresas_mod->first();
    return view('modulo_monitoreo.documents.index', [
      'empresas_mod' => $empresas_mod,
      'empr' => $empr,
      'codes'  => cacheHelper('docstatus.all')
    ]);
  }

  /**
   * Form para buscar documentos
   *
   * @return \Illuminate\Http\Response
   */
  public function processDocs( $id = null )
  {
    $empresas_mod = Empresa::all();
    $empr = $id ? Empresa::find($id) : $empresas_mod->first();
    $status_codes  = cacheHelper('docstatus.all');
    return view('modulo_monitoreo.documents.process', [
      'empr' => $empr,
      'empresas_mod' => $empresas_mod,
      'status_codes' => $status_codes,
    ]);
  }

  /**
   * Buscar documentos
   *
   * @return \Illuminate\Http\Response
   */
  public function processDocsStore(Request $request, $id)
  {
    // return $request->all();
    $empresa = Empresa::find($id);
    $serie = DocSerie::find($request->serie__id);
    $numeroInicial = (int) $request->numero_inicial;
    $numeroFinal =  (int) $request->numero_final;
    $mesCodi =  $request->mes;

    $reprocesar = $request->has('reprocesar');

    $buscador = new BuscadorSunatDocument( $empresa, $serie, $numeroInicial, $numeroFinal, $reprocesar, $mesCodi );
    $empresa->updateCantidadDocs();
    $buscador->procesar();
    $reporte = $buscador->getReporte();
    return response()->json(['data' => $reporte ]);
    
  }
}

