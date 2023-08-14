<?php

namespace App\Http\Controllers;

use App\Sunat;
use App\Venta;
use App\Empresa;
use App\Vendedor;
use App\GuiaSalida;
use App\PDFPlantilla;
use App\GuiaSalidaItem;
use App\MotivoTraslado;
use App\SerieDocumento;
use App\ClienteProveedor;
use App\Models\Guia\Guia;
use Illuminate\Http\Request;
use App\Events\GuiaHasCreate;
use App\Mail\GuiaRemisionMail;
use App\Models\Guia\GuiaIngreso;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Events\GuiaSimplyHasCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\GuiaSaveRequest;
use App\Http\Requests\Guia\StoreSimply;
use App\Jobs\GuiaSalida\ValidateTicket;
use App\Http\Requests\Guia\CreateSimply;
use App\Http\Requests\GuiaUpdateRequest;
use App\Http\Requests\GuiaAnulacionRequest;
use App\Http\Requests\GuiaSentSunatRequest;
use App\Http\Controllers\Guia\GuiaController;
use App\Http\Requests\Guia\GuiaDeleteRequest;
use App\Http\Requests\GuiaSalidaCreateRequest;
use App\Http\Requests\Guia\GuiaSalidaTrasladoRequest;
use App\Http\Requests\Guia\GuiaDespachoIngresoRequest;

class GuiaSalidaController extends GuiaController
{

  public function __construct()
  {
    $this->tipo = Guia::SALIDA;
    $this->model = new GuiaSalida;
    $this->middleware('guia.seriecreada')->only('create', 'edit', 'store', 'pdf');    
    $this->middleware(p_midd('A_GUIA', 'R_REPORTE'))->only('reporte');
    $this->middleware(p_midd('A_INDEX', 'R_GUIASALIDA'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_GUIASALIDA'))->only('create' , 'store', 'createSimply', 'storeSimply');
    $this->middleware(p_midd('A_SHOW', 'R_GUIASALIDA'))->only('show');
    $this->middleware(p_midd('A_IMPRIMIR', 'R_GUIASALIDA'))->only('pdf');
    $this->middleware(p_midd('A_RECURSO', 'R_GUIASALIDA'))->only('file');
    $this->middleware(p_midd('A_EMAIL', 'R_GUIASALIDA'))->only('sentEmail');
    $this->middleware(p_midd('A_DELETE', 'R_GUIASALIDA'))->only('delete');
    $this->middleware(p_midd('A_ANULAR', 'R_GUIASALIDA'))->only('anularGuia');
    $this->middleware(p_midd('A_TRASLADO', 'R_GUIASALIDA'))->only('traslado');
  }

  public function index(Request $request)
  {
    $locales = user_()->locales->load('local');
    $format = $request->input('format', 0);
    $mes = $request->input('mes',  date('Ym'));
    $status = $request->input('status');
    $motivo_traslado = Cache::rememberForever('motivo_traslado' . empcodi(), function () {
      return MotivoTraslado::all();
    });
    return view('guia_remision.index', [
      'format' => $format,
      'locales' => $locales,
      'tipo_documento' => GuiaSalida::TIPO_GUIA_REMISION,
      'mes' => $mes,
      'status' => $status,
      'motivo_traslado' => $motivo_traslado      
    ]);
  }

  public function save(GuiaSalidaCreateRequest $request)
  {
    try {
      $venta = Venta::find($request->id_factura);
      $is_electronica = $request->input('is_electronica', 0);
      $id_guia = GuiaSalida::createGuia($venta->VtaOper, true, $request->id_almacen, $request->id_movimiento,  $is_electronica);
      $agregateInventary = $venta->isNotaCredito();
      foreach ($venta->items as $item) {
        GuiaSalidaItem::createItem($item, $id_guia, 'V', $agregateInventary);
      }
      event(new GuiaHasCreate($id_guia));
    } catch (\Exception $e) {
      throw $e;
    }
    return "Guardado Guia de Salida";
  }

  public function pendientes( Request $request )
  {
    $mes = $request->input('mes' , date('Ym'));
    return view('guia_remision.pendientes', [ 'mes' => $mes ]);
  }
  
  public function getPendientes( $empcodi, $mescodi )
  {
    return GuiaSalida::query()
    ->with([
      'almacen' => function ($q) use ($empcodi) {
        $q->where('EmpCodi', $empcodi);
      },
      'cli' => function ($q) use ($empcodi) {
        $q->where('EmpCodi', $empcodi)
          ->where('TipCodi', 'C')
          ->get();
      }
    ])
    ->where('mescodi', '=', $mescodi )
    ->where('GuiEFor', '=', "1")
    ->where('EntSal', '=', GuiaSalida::SALIDA )    
    ->whereIn('fe_rpta', [ 9, 99, 98] )
    ->orderBy('GuiOper', 'desc');    
  }
  

  public function search_pendientes(Request $request)
  {
    $empcodi = empcodi();
    $busqueda = $this->getPendientes( $empcodi , $request->mes );
    
    return DataTables::of( $busqueda )
      ->addColumn('nrodocumento', 'guia_remision.partials.nrodocumento')
      ->addColumn('estado', 'guia_remision.partials.column_estado')
      ->addColumn('accion', 'guia_remision.partials.column_accion')
      ->rawColumns(['accion', 'estado', 'nrodocumento'])
      ->make(true);
  }

  public function create($id_venta = null)
  {
    $data = $this->acciones('create', null, GuiaSalida::SALIDA);
    $data['importar'] = is_null($id_venta) ? false : Venta::find($id_venta);
    return view('guia_remision.create', $data);
  }

  public function edit(Request $request, $id_guia)
  {
    $id_guia = (int) $id_guia;
    $id_guia = agregar_ceros($id_guia, 6, 0);
    $guia = GuiaSalida::find($id_guia);
    $data = $this->acciones('edit', $id_guia, GuiaSalida::SALIDA);
    $despachar = $request->input('despachar', 0);
    $data['estado_edit'] =  $guia->getEstadoEdicion();
    $data['despachar'] = $despachar;
    return view('guia_remision.edit', $data);
  }


  public function show(Request $request, $id_guia)
  {
    return redirect()->action(
      'GuiaSalidaController@edit',
      [
        'id_guia' => $id_guia,
        'despachar' => $request->has('despachar')
      ]
    );
  }

  public function store(GuiaSaveRequest $request)
  {
    $id_guia = null;
    DB::connection('tenant')->transaction(function () use ($request, &$id_guia){
      $fromVenta = false;
      $data = $request->all();
      $id_guia = GuiaSalida::createGuia($data, $fromVenta, $request->id_almacen, $request->id_movimiento,false,'', $request->fecha_emision, false);
      GuiaSalidaItem::createItems($id_guia, $request->items);
      GuiaSalida::find($id_guia)->calculateTotal();
      event(new GuiaHasCreate($id_guia));
    });
    $route = $request->input('despachar') == "1" ?  route('guia.edit', ['id' => $id_guia, 'despachar' => true]) : route('guia.index');
    noti()->success('Guia Guardada Exitosamente');
    return response()->json(['message' => 'Guia Guardada Exitosamente', 'route_redirect' => $route]);
  }

  public function update(GuiaUpdateRequest $request, $id_guia)
  {
    $guia = GuiaSalida::find( $id_guia );    
    $guia->updateGuia($request);
    noti()->success('Guia Actualizada correctamente');
    $route_redirect = route('guia.index');
    return response()->json(['message' => '', 'route_redirect' => $route_redirect]);
  }

  public function sentSunat(GuiaSentSunatRequest $request, $id_guia)
  {
    // Old
    // $guia = GuiaSalida::find($id_guia);
    // $data = Sunat::sendGuia($request->id_guia);
    // if ($data['status']) {
    //   return $this->guiaSuccess($guia, $data);
    // } else if ($data['status'] == 0 && $data['code'] == 4000) {
    //   $guia->saveSuccess();
    // }
    // return response()->json(['message' =>  $data['message']], $data['code_http']);
    
    $guia = GuiaSalida::find($id_guia);
    $res = $guia->sendApi();
    return response()->json([ 'message' => $res->data ], $res->success ? 200 : 400);
  }

  public function pdf($id_guia, $formato = 'a4')
  {
    $guia = GuiaSalida::find($id_guia);

    if( $guia->isSalida() && $guia->hasFormato() ){
      if( $guia->pendiente() ){
        noti()->warning('Para imprimir La Guia de Remisión, primero tiene que llenar los datos de traslado');
        return redirect()->route('guia.edit', [ 'id' =>  $guia->GuiOper, 'despachar' => true ]);
      }
      else {
        if( $guia->fe_rpta != "0" ){
          // noti()->warning('Para imprimir La Guia de Remisión tiene que estar enviado a la sunat');
          // return redirect()->route('guia.pendientes');
        }
      }
    }

    $namePdf = $guia->nameEnvio('.pdf');
    $fileHelper = filehelper(get_empresa()->ruc());


    if( $formato == PDFPlantilla::FORMATO_A4 ){
      if( $fileHelper->pdfExist($namePdf)) {
        $content = $fileHelper->getPdf($namePdf);
        $path = $fileHelper->saveTemp($content, $namePdf);
        return response()->file($path);
      }
      else {
        $pathTemp = $guia->generatePdf(
          $guia->hasFormato(),
          true,
          $formato
        );
        return response()->file(public_path($pathTemp));
      }

    }
    else {
      $pathTemp = $guia->generatePdf(
        $guia->hasFormato(),
        true,
        $formato
      );
      return response()->file(public_path($pathTemp));
    }
  }

  public function file($type, $id)
  {
    ob_end_clean();
    $guia = GuiaSalida::find($id);;
    $fileHelper = FileHelper(get_empresa('EmpLin1'));
    $exists = null;

    if ($type == 'xml') {
      $nameFile = $guia->nameEnvio('.xml');
      $exists = $fileHelper->xmlExist($nameFile);
    }

    if ($type == 'cdr') {
      $nameFile = 'R-' .  $guia->nameEnvio('.zip');
      $exists = $fileHelper->cdrExist($nameFile);
    }

    if ($exists) {
      $content = $type == 'xml' ? $fileHelper->getEnvio($nameFile) : $fileHelper->getCdr($nameFile);
      $path = $fileHelper->saveTemp($content, $nameFile);
      return response()->download($path);
    }
    return "No se encuentra este recurso";
  }


  public function anularGuia(GuiaAnulacionRequest $request, $guia_id)
  {
    $guia = GuiaSalida::find($request->guia_id);
    
    // $guia->anular();
    $guia->anular(true);

    if( $guia->haSidoTrasladada()){
      // $guia->guiaIngreso->anular();
      $guia->guiaIngreso->anular(true);
    }

    notificacion('Acciòn exitosa', 'Se ha anulado la guia satisfactoriamente');
    return back();
  }

  public function delete($id_guia, GuiaDeleteRequest $request)
  {
    $guia = GuiaSalida::find($id_guia);
    $tipoNombre = $guia->tipoNombre();
    $guia->deleteComplete();

    notificacion("Eliminado", "Se ha eliminado exitosamente la guia {$tipoNombre}", "success");
    return redirect()->back();
  }

  public function sentEmail(Request $request)
  {
    $this->validate($request, [
      'corre_hasta' => 'required',
      'asunto'       => 'required',

    ], [
      'corre_hasta.required' => 'Es necesario colocar el email del cliente',
      'asunto.required' => 'Es necesario colocar el asunto'
    ]);

    $guia = GuiaSalida::find($request->id_guia);

    $data = [
      'subject' => $request->asunto,
      'documento_codi' => $guia->GuiOper,
      'cliente_documento' => $guia->GuiSeri . '-' . $guia->GuiNumee,
      'view' => "mails.enviar_guia",
      'tipo_documento' => "GUIA REMISIÓN",
      'fecha' => $guia->GuiFDes,
      'empresa_codi' => $guia->EmpCodi,
      'mensaje'      => $request->mensaje ?? "",
      'empresa'         => $guia->empresa->EmpNomb,
      'attach'       => [],
    ];

    $fileHelper = fileHelper(get_ruc());

    $nameCDR = $guia->nameCdr();
    $namePDF = $guia->nameEnvio('.pdf');
    $nameXML = $guia->nameEnvio('.xml');

    if ($fileHelper->cdrExist($nameCDR)) {
      $path = $fileHelper->saveTemp($fileHelper->getCdr($nameCDR), $nameCDR);
      array_push($data['attach'], $path);
    }

    if ($fileHelper->pdfExist($namePDF)) {
      $path = $fileHelper->saveTemp($fileHelper->getPdf($namePDF), $namePDF);
      array_push($data['attach'], $path);
    }

    if ($fileHelper->xmlExist($nameXML)) {
      $path = $fileHelper->saveTemp($fileHelper->getEnvio($nameXML), $nameXML);
      array_push($data['attach'], $path);
    }

    return Mail::to($request->corre_hasta)->send(new GuiaRemisionMail($data));
  }

  public function guiaSuccess($guia, $data)
  {
    return self::guiaSuccessMake($guia,$data);
  }

  public static function guiaSuccessMake($guia, $data)
  {
    $data_envio = $guia->successEnvio($data);
    $data = array_merge($data, $data_envio);
    $data['content'] = "";
    $data['message'] = $data[2];
    return response()->json($data, 200);
  }

  public function verificar(Request $request, $id_guia)
  {
    $guia = GuiaSalida::find($id_guia);
    $empresa = $guia->empresa;

    $data = Sunat::verify(
      $empresa->EmpLin1,
      "09",
      $guia->GuiSeri,
      $guia->GuiNumee,
      $guia->nameEnvio()
    );

    if ($empresa->produccion()) {

      if ($data['message'] == 127) {
        $data = Sunat::sendGuia($request->id_guia);

        if ($data['status']) {
          return $this->guiaSuccess($guia, $data);
        }

        return $this->guiaSuccess($guia, $data);
      } else {
        return $this->guiaSuccess($guia, $data);
      }
    }
    return response()->json(['message' => $data['message']], $data['code_http']);
  }

  public function reporte()
  {
    $tipos_documentos = SerieDocumento::ultimaSerie();
    $empresa = Empresa::where('empcodi', session()->get('empresa'))->first();
    $almacenes  = $empresa->almacenes;
    $vendedores = Vendedor::all();
    $clientes = ClienteProveedor::where('Empcodi', empcodi())->get();
    return view('reportes.guias', compact('clientes', 'almacenes', 'vendedores', 'tipos_documentos'));
  }

  public function createSimply(CreateSimply $request, $id)
  {
    $venta = Venta::with('items')->find($id);
    return view('guia_remision.partials.create_simply', [
      'venta' => $venta,
      'items' => $venta->items,
      'almacenes' => auth()->user()->locales
    ]);
  }

  public function storeSimply(StoreSimply $request, $id)
  {
    event(new GuiaSimplyHasCreated($request->all(), Venta::findOrfail($id)));
    return response()->json([
      'message' => 'Se ha creado exitosamente la guìa'
    ]);
  }  

  public function traslado(GuiaSalidaTrasladoRequest $request, $id)
  {
    $guia_ingreso_id = GuiaSalida::findOrfail($id)->traslado( $request->all() );
    $route = route('guia_ingreso.edit', $guia_ingreso_id);

    if ($request->input('json_response')) {
      $data = [];
      return response()->json($data);
    }

    $message = "Se ha realizado el traslado exitosamente <a target='_blank' href='{$route}'> Enlace aqui  </a> ";
    notificacion('Accion exitosa', $message, 'success');
    return back()->with('N_hideAfter', false);
  }

  public function saveSuccessValidacion( $id )
  {
    GuiaSalida::find($id)->saveSuccess();
    notificacion('Acciòn exitosa', 'Se han validado la guia exitosamente', 'success');
    return back();
  }

  public function prepareGuias( $mescodi )
  {
    $guia_id = env('GUIA_SALIDA_PLANTILLA');
    // loremp-ipsum-odlor-loremp-ipsum-odlor
    $pendientes = $this->getPendientes( empcodi(), $mescodi );
    $ids = $pendientes->get()->pluck('GuiOper');
    GuiaSalida::prepareGuias( $ids , $guia_id );
    notificacion('Acciòn exitosa', 'Se han prearado exitosamente', 'success');
    return back();
  }

  public function despacho(GuiaDespachoIngresoRequest $request, $id_guia)
  {
    $guia = $this->model->find($id_guia);
    $guia->saveDespacho($request->all());
    $guia->fresh()->saveDespacho($request->all());
    $guia->createXmlZip();

    return response()->json([
      'message' => 'Información de despacho guardado exitosamente',
      'route_impresion' => route('guia.pdf', $guia->GuiOper)
    ]);
  }

  public function consultTicket( Request $request, $id)
  {
    $guia = GuiaSalida::findOrfail($id);

    if( $guia->isIngreso() ){
      return response()->json(['message' => 'Tiene que ser una guia de salida enviada a la sunat'], 400 );
    }

    if( !$guia->hasFormato() ){
      return response()->json(['message' => 'Esta guia no es una Guia de Remisión Electronica'], 400);
    }

    if (!$guia->isCerrada()) {
      $link = route('guia.edit', ['id' =>  $guia->GuiOper, 'despachar' => true]);
      return response()->json(['message' => sprintf('Esta guia falta realizar su <a href="%s">DESPACHO</a>', $link  )  ], 400);
    }

    if (!$guia->fe_ticket ) {
      $link = route('guia.pendientes');
      return response()->json(['message' => sprintf('Esta guia no tiene ticket asociado, por favor procesar a enviarla en <a href="%s">PENDIENTES</a>', $link)], 400);
    }
    
    $res = (new ValidateTicket($guia))
    ->handle()
    ->getResult();

    return response()->json([ 'message' => $res->data ], $res->success ? 200 : 400);
  }

}