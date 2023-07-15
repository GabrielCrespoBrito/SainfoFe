<?php

namespace App\Http\Controllers\Guia;

use App\GuiaSalida;
use App\MotivoTraslado;
use App\Models\Guia\Guia;
use Illuminate\Http\Request;

class GuiaTrasladoController extends GuiaController
{
  public $model;
  public $tipo;

  public function __construct()
  {
    $this->model = new GuiaSalida();
    $this->middleware(p_midd('A_TRASLADO', 'R_GUIASALIDA'));
  }

  public function search(Request $request)
  {
    $term = $request->input('search')['value'];
    $mes = $request->mes;
    $tipo = $request->tipo;
    $local = $request->local;    
    $is_salida = $tipo == GuiaSalida::SALIDA;
    $estado = $is_salida ? $request->estado_salida : $request->estado_ingreso;
    $nombre_estado_campo = $is_salida ? 'e_traslado' : 'e_conformidad';
    $busqueda =
      $this->model
      ->mes($mes)
      ->tipo($tipo)
      ->motivo(MotivoTraslado::TRASLADO_MISMA_EMPRESA);

    if($local){
      $busqueda->local($local);
    }

    // Filtrar campo
    if($estado){
      $busqueda->where($nombre_estado_campo, '=', $estado);
    }

    if (!is_null($term)) {
      $busqueda = $busqueda
        ->id($term)
        ->get();
    }

    $datatable = datatables()->of($busqueda)
    ->addColumn('nrodocumento', 'guia_remision.partials.nrodocumento')
    ->addColumn('correlativo', 'guia_remision.guia_traslado.partials.column_correlativo');

    $rawColumns =  ['nrodocumento','correlativo'];

    if( $is_salida ){
      $datatable->addColumn('estado_traslado', 'guia_remision.guia_traslado.partials.column_estado_traslado');
      $datatable->addColumn('guia_traslado', 'guia_remision.guia_traslado.partials.column_guia_traslado');
      array_push( $rawColumns, 'estado_traslado', 'guia_traslado' );
    }
    else {
      $datatable->addColumn('estado_conformidad', 'guia_remision.guia_traslado.partials.column_estado_conformidad');
      $datatable->addColumn('observaciones', 'guia_remision.guia_traslado.partials.column_observaciones');
      $datatable->addColumn('guia_traslado', 'guia_remision.guia_traslado.partials.column_guia_traslado');
      array_push($rawColumns, 'estado_conformidad', 'observaciones', 'guia_traslado');
    }

    return $datatable
    ->rawColumns( $rawColumns )    
    ->make(true);
  }




  public function index(Request $request)
  {
    $tipo = $request->input('tipo', GuiaSalida::INGRESO);

    return view('guia_remision.guia_traslado.index', [
      'format' => $request->input('format', false),
      'locales' => get_empresa()->locales,
      'tipo' => $tipo,
      'salida' => $tipo === GuiaSalida::SALIDA,
    ]);
  }

  public function create($id_venta = null)
  {
    $data = $this->acciones('create', null, Guia::INGRESO);
    return view('guia_remision.guia_ingreso.create', $data);
  }

  public function edit($id_guia)
  {
    $guia = GuiaSalida::find($id_guia);
    $data = $this->acciones('edit', $id_guia, Guia::INGRESO);
    return view('guia_remision.guia_ingreso.edit', $data);
  }


  

  public function show($id_guia)
  {
    return redirect()->action(
      'GuiaSalidaController@edit',
      ['id_guia' => $id_guia]
    );
  }
}
