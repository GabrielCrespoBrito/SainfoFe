<?php

namespace App\Http\Controllers;

use App\Zona;
use App\Venta;
use App\Moneda;
use App\TipoPago;
use App\Vendedor;
use App\FormaPago;
use App\Cotizacion;
use App\ListaPrecio;
use App\TipoCliente;
use App\PDFPlantilla;
use PermissionSeeder;
use App\EmpresaOpcion;
use App\TipoDocumento;
use App\CondicionVenta;
use App\CotizacionItem;
use App\SerieDocumento;
use App\TipoMovimiento;
use App\TipoNotaCredito;
use Barryvdh\DomPDF\PDF;
use App\ClienteProveedor;
use App\TipoCambioMoneda;
use App\Models\Tienda\Orden;
use Illuminate\Http\Request;
use App\Models\Tienda\MetaData;
use Illuminate\Support\Facades\DB;
use App\Woocomerce\WoocomerceOrder;
use App\Util\PDFGenerator\PDFGenerator;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ImportVentaRequest;
use App\Http\Requests\CotizacionSaveRequest;
use App\Jobs\Cotizacion\FormatTiendaCotizacion;
use App\Http\Requests\CotizacionAnulacionRequest;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class CotizacionAbstractController extends Controller
{
  public $tipo;
  public $isOrdenCompra;
  public $routes;

  public function setRoutes($id = null)
  {
    $routes = [];

    if ($this->isOrdenCompra) {
      $routes = [
        'create' => route('orden_compras.create'),
        'edit' => route('orden_compras.edit', 'XX'),
        'delete' => route('coti.delete', 'XX'),
        'routes'  => $this->getRoutes()
      ];
    } else {
      $routes = [
        'create' => route('coti.create', ['tipo' => $this->tipo]),
        'edit' => route('coti.edit', 'XX'),
        'delete' => route('coti.delete', 'XX'),
        'routes'  => $this->getRoutes()
      ];
    }

    return $this->routes = $routes;
  }

  public function getRoutes()
  {
    return (object) $this->routes;
  }

  public function __construct($isOrdenCompra = true)
  {
    $this->isOrdenCompra = $isOrdenCompra;
    $this->setPermissions();
  }

  public function setPermissions()
  {
    if ($this->isOrdenCompra) {
      $this->middleware(p_midd('A_INDEX', 'R_ORDENCOMPRA'))->only('index');
      $this->middleware(p_midd('A_SHOW', 'R_ORDENCOMPRA'))->only('show');
      $this->middleware(p_midd('A_IMPRIMIR', 'R_ORDENCOMPRA'))->only('pdf');
      $this->middleware(p_midd('A_CREATE', 'R_ORDENCOMPRA'))->only(['create', 'save']);
      $this->middleware(p_midd('A_EDIT', 'R_ORDENCOMPRA'))->only(['edit', 'update']);
      return;
    }

    $this->middleware(p_midd('A_INDEX', 'R_PREVENTA'))->only('index');
    $this->middleware(p_midd('A_SHOW', 'R_PREVENTA'))->only('show');
    $this->middleware(p_midd('A_IMPRIMIR', 'R_PREVENTA'))->only('pdf');
    $this->middleware(p_midd('A_CREATE', 'R_PREVENTA'))->only(['create', 'save']);
    $this->middleware(p_midd('A_EDIT', 'R_PREVENTA'))->only(['edit', 'update']);
    return;
  }

  public function setTipo($tipo)
  {
    $this->tipo = $tipo;
  }

  public function getTipo()
  {
    return $this->tipo;
  }

  public function index($tipo = null)
  {
    $this->setTipo($tipo ?? Cotizacion::ORDEN_COMPRA);
    $tipo = $this->getTipo();
    $this->setRoutes();

    $titulo_pagina = Cotizacion::getNombre($tipo);
    $locales = user_()->locales->load('local');
    $vendedores = get_empresa()->vendedores;
    $usuarios = get_empresa()->users;
    return view('cotizaciones.index', [
      'tipo' => $tipo,
      'locales' => $locales,
      'usuarios' => $usuarios,
      'vendedores' => $vendedores,
      'titulo_pagina' => $titulo_pagina,
      'routes' => $this->getRoutes(),
    ]);
  }

  public function getCotizacion(ImportVentaRequest $request)
  {
    $nume = $request->serie_documento . '-' . $request->numero_documento;
    $is_cotizacion = true;
    $documento = Cotizacion::findByNume($nume);

    if (is_null($documento)) {
      $is_cotizacion = false;
      $documento = Venta::findByNume($nume);
    }

    $items = [];
    $cliente_info = [];

    // Cliente
    $cliente = $documento->cliente;
    $cliente_info['PCCodi']  = $cliente->PCCodi;
    $cliente_info['PCRucc']  = $cliente->PCRucc;
    $cliente_info['PCNomb']  = $cliente->PCNomb;
    $cliente_info['PCDire']  = $cliente->PCDire;
    $cliente_info['PCMail']  = $cliente->PCMail;
    $cliente_info['tipo_documento']  = $cliente->tipo_documento_c->TdocNomb;


    // dd($documento->items->sortBy('DetItem'));
    // exit();

    // dd( $documento->load(['items' => function($q){
    // }])  items->sortBy('DetItem') );
    // exit();

    // ->orderByRaw('section * 1 desc') 

    $count = $documento->items->count();

    foreach ($documento->items as $item) {
      $data = [];
      $data['Index'] = (int) $item->DetItem;
      $data['Unidades'] = $item->producto->unidades;
      $data['Marca'] = $item->MarNomb;
      $data['cliente'] = $documento->MarNomb;
      $data['MarCodi'] = $item->producto->marcodi;
      $data['TieCodi'] = $item->producto->tiecodi;
      $data['DetPeso'] = $item->producto->ProPeso;
      $data['DetCome'] = $is_cotizacion ? $item->DetDeta : $item->DetCome;
      $data['UniCodi'] = $item->UniCodi;
      $data['DetCodi'] = $item->DetCodi;
      $data['DetNomb'] = $item->DetNomb;
      $data['DetUni']  = $item->DetUnid;
      $data['DetUniNomb'] = $item->DetUnid;
      $data['DetCant'] = $item->DetCant;
      $data['DetPrec'] = $item->DetPrec;
      $data['DetDcto'] = $item->DetDcto;
      $data['DetPercP'] = $item->DetPercP;
      $data['DetBase'] = $item->DetBase;
      $data['DetIGVP'] = $item->DetIGVP ?? 0;
      $data['DetPvta'] = 0;
      $data['DetIGVV'] = $item->DetIGVV;
      $data['DetISC']  = $item->DetISC;
      $data['DetISCP']  = $item->DetISCP;
      $data['DetISP']  = $item->DetISCP;
      $data['TipoIGV'] = $item->TipoIGV;
      $data['DetImpo'] = $item->DetImpo;
      $data['DetPercV'] = $item->DetPercV;
      $data['incluye_igv'] = $item->incluye_igv;
      $data['icbper_value'] = $item->icbper_value;
      $data['icbper'] = $item->icbper_unit;
      $data['DetISC'] = $item->cotisc;
      array_push($items, $data);
    }

    // $items2 = $items;

    if( $count > 99 ){
      $items2 = collect($items)->sortBy('Index')->toArray();
      $items = [];
      foreach( $items2 as $item ){
      array_push($items, $item);
      }
    }

    // dd( $items, $items2 );
    // exit();

    $data = [
      'nume' => $nume,
      'vendedor' => $documento->vencodi,
      'ZonCodi' => $documento->zoncodi,
      'items' => $items,
      'moneda' => $documento->getMoneda(),
      'table' => $documento->table,
      'cliente' => $cliente_info,
    ];

    return $data;
  }

  public function search(Request $request)
  {
    $tipoCliente = $request->tipo == Cotizacion::ORDEN_COMPRA ? ClienteProveedor::TIPO_PROVEEDOR : ClienteProveedor::TIPO_CLIENTE;

    $withData = ['moneda', 'venta', 'forma_pago', 'usuario', 'cliente_with' => function ($q) use ($tipoCliente) {
      $q->where('TipCodi', $tipoCliente);
    }];

    if ($request->withItems) {
      $withData[] = "items.producto.unidades";
    }

    $busqueda = Cotizacion::query()->with($withData);

    $term = $request->search['value'];

    if ($request->mes) {
      $busqueda->where('MesCodi', $request->mes);
    }

    if ($request->tipo) {
      $busqueda->where('TidCodi1', $request->tipo);
    }

    if ($request->local) {
      $busqueda->where('LocCodi', $request->local);
    }

    if ($request->estado) {
      $busqueda->where('cotesta', $request->estado);
    }

    if ($request->vendedor) {
      $busqueda->where('vencodi', $request->vendedor);
    }

    if ($request->usucodi) {
      $busqueda->where('usucodi', $request->usucodi);
    }

    if ($term) {
      $busqueda->where('CotNume', 'LIKE', '%' . $term)->get();
    }

    $busqueda->orderBy('CotNume', 'desc');

    return DataTables::of($busqueda)
      ->addColumn('accion', 'cotizaciones.partials.column_accion')
      ->addColumn('estado', 'cotizaciones.partials.column_estado')
      ->addColumn('venta', 'cotizaciones.partials.column_venta')
      ->rawColumns(['accion', 'estado', 'venta'])
      ->make(true);
  }

  public function cotizacion_accion($id_cotizacion = null, $accion = "create", $request)
  {
    $tipo = $request->input('tipo', Cotizacion::ORDEN_COMPRA);
    $decimales = getEmpresaDecimals();

    $empresa =  get_empresa();
    $data = [
      'is_orden' => false,
      'import' => false,
      'importHabilitado' => false,
      'ver_costos' => auth()->user()->checkPermissionTo_(concat_space(PermissionSeeder::A_VERCOSTOS, PermissionSeeder::R_PRODUCTO)),
      'tipo' => $tipo,
      'lista_precios' => ListaPrecio::all(),
      'ruc' => "",
      'canModifyPrecios'  => 1,
      'tipos_clientes' => TipoCliente::all(),
      'tipos_documentos_clientes' => TipoDocumento::all(),
      'tipo_pagos' => TipoPago::all(),
      'bancos' => $empresa->bancos->groupBy('BanCodi'),
      'almacenes' => $empresa->almacenes,
      'igvEmpresa' => $empresa->getIgvPorc(),
      'grupos' => cacheHelper('grupo.all'),
      EmpresaOpcion::MODULO_MANEJO_STOCK => $empresa->getDataAditional(EmpresaOpcion::MODULO_MANEJO_STOCK),
      'locales' => auth()->user()->locales->load('local'),
      'tipo_movimientos' => TipoMovimiento::activos(),
      "vendedores"  => Vendedor::all(),
      "zonas"  => Zona::all(),
      "tipos_documentos" => SerieDocumento::ultimaSerie(),
      "forma_pagos" => FormaPago::all(),
      'decimales_dolares' => $decimales->dolares,
      'decimales_soles' => $decimales->soles,
      "empresa"     => $empresa,
      "monedas"   => Moneda::all(),
      "condicion"   => $condicion = CondicionVenta::getDefaultCotizacion(),
      'condicion_cot' => $condicion,
      "tipo_cambio" => TipoCambioMoneda::ultimo_cambio(false),
      "tipos_notacredito" => TipoNotaCredito::all(),
      'cursor_pointer_producto' => get_option(EmpresaOpcion::CAMPO_CURSOR_PRODUCTO)
    ];

    if ($accion == "create") {
      $isOrdenCompra = $tipo == Cotizacion::ORDEN_COMPRA;
      $data['id'] = Cotizacion::UltimoId($tipo);
      $data["create"] = 1;
      $data["importHabilitado"] = get_empresa()->getDataAditional('woocomerce_api_url');
      $data['tipo'] = $tipo;
      $data['tipo_condicion'] = $isOrdenCompra ? 'orden_compra' : 'cotizacion';
      $data['modify'] = false;
      $data['cliente_id'] = '';
      $data['cliente_descripcion'] = '';
      $data['cliente_tipo'] = '';
      $data['routeIndex'] = $isOrdenCompra ? route('orden_compras.index') : route('coti.index', ['tipo' => $tipo]);
      $data['routeCreate'] = $isOrdenCompra ?  route('coti.save') : route('orden_compras.store');
      $data['nombre_entidad'] = $isOrdenCompra ? 'Proveedor' : 'Cliente';
      $data['cliente_entidad'] = $isOrdenCompra ? ClienteProveedor::TIPO_PROVEEDOR : ClienteProveedor::TIPO_CLIENTE;
      $data['routeSearchCliente'] = $isOrdenCompra ?  route('proveedor.search') : route('clientes.ventas.search');
      $data['is_preventa'] = $request->tipo == Cotizacion::PREVENTA;
      $data['titulo'] = $request->tipo == Cotizacion::PREVENTA;

      if ($id = $request->input('importar')) {
        $data = (new FormatTiendaCotizacion($id, $data))->handle();
      }

    } else {
      $cotizacion = Cotizacion::find($id_cotizacion);
      $isOrdenCompra = $cotizacion->isOrdenCompra();
      $cliente = $cotizacion->cliente;
      $data['cliente_id'] = $cliente->PCCodi;
      $data['tipo_condicion'] = $isOrdenCompra ? 'orden_compra' : 'cotizacion';
      $data['cliente_descripcion'] = $cliente->descripcion;
      $data['cliente_tipo'] =  TipoDocumento::getNombre($cliente->TdoCodi);
      $data['cliente_entidad'] = $isOrdenCompra ? ClienteProveedor::TIPO_PROVEEDOR : ClienteProveedor::TIPO_CLIENTE;
      $data['routeIndex'] = $isOrdenCompra ? route('orden_compras.index') : route('coti.index', ['tipo' => $cotizacion->TidCodi1]);
      $data['routeCreate'] =  $cotizacion->getRouteCreate();
      $data['routeSearchCliente'] = $isOrdenCompra ?  route('proveedor.search') : route('clientes.ventas.search');
      $data["condicion"] = $cotizacion->CotCond;
      $data['cotizacion'] = $cotizacion;
      $data['nombre_entidad'] = $isOrdenCompra ? 'Proveedor' : 'Cliente';
      $data['is_preventa'] =  $cotizacion->isPreventa();
      $data['tipo'] = $cotizacion->TidCodi1;
      $data['create'] = 0;
      $data['modify'] = true;
      $tipo = $cotizacion->TidCodi1;
    }

    $data['nombre'] = Cotizacion::getNombre($tipo);
    $data['titulo'] = $accion == "create" ?
      'Crear nueva ' . $data['nombre'] :
      'Modificar ' . $cotizacion->CotNume;
    $data['tipoNombre'] = Cotizacion::getTipoNombre($tipo);

    if ($request->has('orden_id')) {
      $data['is_orden'] = true;
      $data['orden'] = Orden::with(['stat.cliente.user', 'items.producto.info', 'info'])->findOrfail($request->input('orden_id'));
      $data['orden_cliente'] =  $data['orden']->getClienteSainfoOrData();
    }

    // dd($data['importInfo']);
    // exit();

    return view('cotizaciones.crear_modificar', $data);
  }

  public function update(CotizacionSaveRequest $request)
  {
    try {
      DB::connection('tenant')->beginTransaction();
      $cotizacion = Cotizacion::guardar($request->all(), $request->id_cotizacion, $request->total_documento, null);
      CotizacionItem::guardar($request->items, $cotizacion, false, $request->totales_items);
      $cotizacion->generatePDF(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR, get_empresa()->hasImpresionIGV(), true, true);
      DB::connection('tenant')->commit();
    } catch (\Exception $e) {
      DB::connection('tenant')->rollback();
      return response()->json('Error al guardar la cotización (ex) ' . $e->getMessage(), 500);
    } catch (\Throwable $e) {
      DB::connection('tenant')->rollback();
      return response()->json('Error al guardar la cotización (th)' . $e->getMessage(), 500);
    } catch (FatalThrowableError $e) {
      DB::connection('tenant')->rollback();
      return response()->json('Error al guardar la cotización (fa)' . $e->getMessage(), 500);
    }
  }

  public function create(Request $request)
  {
    $tipo = $request->input('tipo', Cotizacion::ORDEN_COMPRA);
    $seriesCount = SerieDocumento::findSerie(empcodi(), null, $tipo, auth()->user()->localCurrent()->loccodi)->count();
    // 
    if ($seriesCount == 0) {
      noti()->info('No tiene series registradas para este tipo de documento');
      return redirect()->back();
    }

    $data = $this->cotizacion_accion(null, "create", $request);
    $data['observacion'] = $request->tipo  == Cotizacion::NOTACOMPRA ? '* Este documento NO ES COMPROBANTE DE PAGO, se emitirá una FACTURA ELECTRÓNICA luego del pago. Revisar los datos del RUC y DENOMINACIÓN de esta orden' : '';

    return $data;
  }

  public function edit(Request $request, $id_cotizacion)
  {
    $cotizacion = Cotizacion::find($id_cotizacion);

    if ($cotizacion->isFacturado()) {
      noti()->info($cotizacion->nombreDocumento() .  '  ya ha sido facturado');
      return back();
    }

    if ($cotizacion->isAnulado()) {
      noti()->info($cotizacion->nombreDocumento() .  '  ya ha sido anulado');
      return back();
    }

    $data = $this->cotizacion_accion($id_cotizacion, "edit", $request);

    return $data;
  }

  public function show($id_cotizacion)
  {
    $data = $this->cotizacion_accion($id_cotizacion, "edit", null);
    return $data;
  }

  public function save(CotizacionSaveRequest $request)
  {
    $success = false;
    $series  = $request->series;
    DB::connection('tenant')->beginTransaction();
    $cotizacion = null;
    try {
      $cotizacion = Cotizacion::guardar($request->all(), false, $request->total_documento, $series);
      CotizacionItem::guardar($request->items, $cotizacion, false, $request->totales_items);
      $success = true;
      DB::connection('tenant')->commit();
    } catch (\Exception $e) {
      DB::connection('tenant')->rollBack();
      return response()->json('Error al guardar la cotización (ex) ' . $e->getMessage(), 500);
    } catch (\Throwable $e) {
      DB::connection('tenant')->rollBack();
      return response()->json('Error al guardar la cotización (th)' . $e->getMessage(), 500);
    } catch (FatalThrowableError $e) {
      DB::connection('tenant')->rollBack();
      return response()->json('Error al guardar la cotización (fa)' . $e->getMessage(), 500);
    }

    if ($success) {

      foreach ($series as $serie) {
        $serie->updateNextNumber();
      }
      $cotizacion->generatePDF(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR, get_empresa()->hasImpresionIGV(), true, true);

      if ( $request->has('import_id') ) {
          $woocomerce = (new WoocomerceOrder)->update($request->input('import_id'), ['status' => 'completed']);
          if(!$woocomerce->success){
          }
          // $orden = Orden::findOrfail($request->import_id);
        // $orden->saveCompleteStatus();
        // $orden->info()->create(['meta_key' => MetaData::COTIZACION_ID, 'meta_value' => $cotizacion->getId()]);

      }
    }
  }

  public function delete(Request $request,  $id_cotizacion)
  {
    $cotizacion = Cotizacion::findOrfail($id_cotizacion);

    $permission =  $cotizacion->isOrdenCompra() ? p_name('A_DELETE', 'R_ORDENCOMPRA')  : p_name('A_DELETE', 'R_PREVENTA');

    $this->authorize($permission);
    if ($cotizacion->isFacturado()) {
      noti()->error('No se puede Anular porque ya esta Facturado');
      return back();
    }

    $nombreDocumento = $cotizacion->nombreDocumento();
    $result = $cotizacion->deleteComplete();
    $result->success ?
      noti()->success("Se ha eliminado la cotización satisfactoriamente") :
      noti()->warning("No se ha podido eliminar la {$nombreDocumento}:" . $result->message);

    return redirect()->back();
  }

  public function pdf(
    $tipo = 1,
    $id_cotizacion,
    $formato = PDFPlantilla::FORMATO_A4
  ) {
    ob_end_clean();
    ob_start();
    $cotizacion = Cotizacion::find($id_cotizacion);
    $namePDF = $cotizacion->nameFile('.pdf', true);
    $pathTemp = file_build_path('temp', $namePDF);
    $empresa = get_empresa();
    $fileHelper = fileHelper($empresa->ruc());
    $impresionConIgv = $empresa->hasImpresionIGV();
    $tipo = $tipo == "igv";

    if ($tipo == $impresionConIgv) {
      if ($fileHelper->pdfExist($namePDF)) {
        if ($formato == PDFPlantilla::FORMATO_A4) {
          \File::put(public_path($pathTemp), $fileHelper->getPdf($namePDF));
        } else {
          $pathTemp = $cotizacion->generatePDF($formato, PDFGenerator::HTMLGENERATOR, $tipo,  true, false);
        }
      } else {
        $pathTemp = $cotizacion->generatePDF($formato, PDFGenerator::HTMLGENERATOR, $tipo,  true, true);
      }
    } else {
      $pathTemp = $cotizacion->generatePDF($formato, PDFGenerator::HTMLGENERATOR, $tipo,  true, false);
    }

    return response()->file($pathTemp, [
      'Content-Description' => 'File Transfer',
      'Content-Disposition' => 'filename=' . $cotizacion->CotNume . '.pdf'
    ]);
  }

  public function anular(CotizacionAnulacionRequest $request, $id_cotizacion)
  {
    $this->authorize(p_name('A_LIBERAR', 'R_PREVENTA'));
    Cotizacion::findOrfail($id_cotizacion)->setAnulado();
    noti()->success('Documento Anulado Exitosamente');
    return back();
  }
}
