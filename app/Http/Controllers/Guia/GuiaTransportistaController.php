<?php

namespace App\Http\Controllers\Guia;

use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\Models\Guia\Guia;
use Illuminate\Http\Request;
use App\Events\GuiaHasCreate;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GuiaSaveRequest;
use App\Http\Requests\Guia\GuiaTransportistaDespachoRequest;

class GuiaTransportistaController extends GuiaController
{
  public $model;
  public $tipo;

  public function __construct()
  {
    $this->model = new GuiaSalida();
    $this->tipo = GuiaSalida::SALIDA;
    $this->middleware('guia.seriecreada:' . GuiaSalida::TIPO_GUIA_TRANSPORTISTA)->only('create', 'edit', 'store');
    $this->middleware(p_midd('A_GUIA', 'R_REPORTE'))->only('reporte');
    $this->middleware(p_midd('A_INDEX', 'R_GUIATRANSPORTISTA'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_GUIATRANSPORTISTA'))->only('create', 'store', 'createSimply', 'storeSimply');
    $this->middleware(p_midd('A_SHOW', 'R_GUIATRANSPORTISTA'))->only('show');
    $this->middleware(p_midd('A_IMPRIMIR', 'R_GUIATRANSPORTISTA'))->only('pdf');
    $this->middleware(p_midd('A_RECURSO', 'R_GUIATRANSPORTISTA'))->only('file');
    $this->middleware(p_midd('A_EMAIL', 'R_GUIATRANSPORTISTA'))->only('sentEmail');
    $this->middleware(p_midd('A_DELETE', 'R_GUIATRANSPORTISTA'))->only('delete');
    $this->middleware(p_midd('A_ANULAR', 'R_GUIATRANSPORTISTA'))->only('anularGuia');
  }

  public function index(Request $request)
  {
    $tipo = $request->input('tipo', GuiaSalida::SALIDA);
    
    return view('guia_remision.guia_transportista.index', [
      'format' => $request->input('format', false),
      'locales' => auth()->user()->locales->load('local'),
      'tipo_documento' => GuiaSalida::TIPO_GUIA_TRANSPORTISTA,
      'salida' => $tipo === GuiaSalida::SALIDA,
    ]);
  }

  public function create($id_venta = null)
  {
    $data = $this->acciones('create', null, Guia::SALIDA, GuiaSalida::TIPO_GUIA_TRANSPORTISTA );
    return view('guia_remision.create', $data);
  }

  public function edit( Request $request, $id_guia)
  {
    $id_guia = (int) $id_guia;
    $id_guia = agregar_ceros($id_guia, 6, 0);
    $guia = GuiaSalida::find($id_guia);
    $data = $this->acciones('edit', $id_guia, GuiaSalida::SALIDA, GuiaSalida::TIPO_GUIA_TRANSPORTISTA);
    $despachar = $request->input('despachar', 0);
    $data['estado_edit'] =  $guia->getEstadoEdicion();
    $data['despachar'] = $despachar;
    return view('guia_remision.edit', $data);
  }

  public function despacho(GuiaTransportistaDespachoRequest $request, $id_guia)
  {
    $guia = $this->model->find($id_guia);
    $guia->saveDespacho($request->all());
    return response()->json([
      'message' => 'Información de despacho guardado exitosamente',
      'route_impresion' => route('guia.pdf', $guia->GuiOper)
    ]);
  }

  public function store(GuiaSaveRequest $request)
  {
    // ---------------------------------------------------------------------------------------------------------
    $id_guia = null;
    DB::transaction(function () use ($request, &$id_guia) {
      $fromVenta = false;
      $data = $request->all();
      $id_guia = GuiaSalida::createGuia($data, $fromVenta, $request->id_almacen, $request->id_movimiento, false, '', $request->fecha_emision, false, GuiaSalida::TIPO_GUIA_TRANSPORTISTA);
      GuiaSalidaItem::createItems($id_guia, $request->items);
      GuiaSalida::find($id_guia)->calculateTotal();
      event(new GuiaHasCreate($id_guia));
    });

    $route = $request->input('despachar') == "1" ?  
      route('guia_transportista.edit', ['id' => $id_guia, 'despachar' => true]) : 
      route('guia_transportista.index');

    noti()->success('Guia Guardada Exitosamente');
    return response()->json(['message' => 'Guia Guardada Exitosamente', 'route_redirect' => $route]);
  }

  public function show($id_guia)
  {
    return redirect()->action('GuiaSalidaController@edit',[ 
      'id_guia' => $id_guia
    ]);
  }
}