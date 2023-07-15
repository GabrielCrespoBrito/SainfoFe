<?php

namespace App\Http\Controllers;

use App\Sunat;
use App\Venta;
use Exception;
use Throwable;
use App\TipoIgv;
use App\TipoPago;
use App\VentaItem;
use App\Cotizacion;
use App\ErrorSunat;
use App\GuiaSalida;
use App\BancoEmpresa;
use App\PDFPlantilla;
use App\EmpresaOpcion;
use App\CondicionVenta;
use App\CotizacionItem;
use App\SerieDocumento;
use App\TipoMovimiento;
use App\ClienteProveedor;
use App\TipoDocumentoPago;
use App\TipoCambioPrincipal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MedioPago\MedioPago;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Requests\VentasItemRequest;
use App\Repositories\TipoPagoRepository;
use App\Repositories\TiposIGVRepository;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\FacturaSaveRequest;
use App\Http\Requests\VentaDeleteRequest;
use App\Repositories\MedioPagoRepository;
use App\Models\Suscripcion\Caracteristica;
use App\Http\Requests\ClienteExistsRequest;
use App\Repositories\BancoCuentaRepository;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Http\Requests\Venta\AnularNotaVentaRequest;
use App\Http\Requests\VentaCreateNotaDebitoRequest;
use App\Http\Requests\VentaCreateNotaCreditoRequest;
use App\Repositories\CondicionCompraVentaRepository;

class VentasController extends Controller
{
  public $fileHelper;

  function __construct()
  {
    // Comment branch dev
    $this->fileHelper = FileHelper();
    $this->middleware(p_midd('A_INDEX',    'R_VENTA'))->only('index', 'search');
    $this->middleware(p_midd('A_SHOW',     'R_VENTA'))->only('edit', 'show');
    $this->middleware([p_midd('A_CREATE',  'R_VENTA'), 'caja.aperturada'])->only('create_factura');
    $this->middleware(p_midd('A_IMPRIMIR', 'R_VENTA'))->only('imprimirFactura');
    $this->middleware(p_midd('A_RECURSO',  'R_VENTA'))->only('verXml', 'verCdr', 'recursoAnulacion', 'getFiles');
  }

  public function index(Request $request)
  {
    $locales = user_()->locales->load('local');
    $tipo_movimiento  = new TipoMovimiento();
    $tipo_movimientos  =  $tipo_movimiento->repository()->where('Tmocodi',  TipoMovimiento::DEFAULT_SALIDA);
    return view('ventas.index', ['tipo' => 'todos', 'locales' => $locales, 'tipo_movimientos' => $tipo_movimientos]);
  }

  public function searchCanje(Request $request)
  {
    $local = auth()->user()->local();
    $busqueda = Venta::query()->with(
      ['cliente_with' => function ($q) {
        $q->where('TipCodi', 'C');
      }, 'items.unidad', 'moneda']
    )
      ->where('VtaFMail', StatusCode::CODE_EXITO_0001)
      ->where('TipoOper', Venta::TIPO_NORMAL)
      ->where('TidCodi', Venta::NOTA_VENTA)
      ->where('LocCodi', $local)
      ->whereNull('VtaOperC');

    $dataTable = DataTables::of($busqueda);
    return $dataTable->make(true);
  }

  public function search(Request $request)
  {
    $term = $request->input('search')['value'];
    $estado = $request->input("estado");
    $status = $request->input("status");
    $filter = false;
    $busqueda = DB::connection('tenant')->table('ventas_cab')
      ->join('prov_clientes', function ($join) {
        $join
          ->on('prov_clientes.PCCodi', '=', 'ventas_cab.PCCodi')
          ->where('prov_clientes.TipCodi', '=', 'C');
      })
      ->join('local', function ($join) {
        $join
          ->on('local.LocCodi', '=', 'ventas_cab.LocCodi')
          ->on('local.EmpCodi', '=', 'ventas_cab.EmpCodi');
      })
      ->join('moneda', 'moneda.moncodi', '=', 'ventas_cab.MonCodi')
      ->where('ventas_cab.EmpCodi', '=', empcodi())
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
        'ventas_cab.fe_obse',
        'ventas_cab.VtaFvta',
        'prov_clientes.PCNomb',
        'prov_clientes.PCRucc',
        'moneda.monabre'
      );

    if ($status != null) {
      $busqueda->where('ventas_cab.VtaFMail', '=', $status);
    }

    if ($request->input("buscar_por_fecha") == "1") {
      $busqueda = $busqueda->whereBetween('ventas_cab.VtaFvta', [$request->input('fecha_desde'), $request->input('fecha_hasta')]);
    }

    if ($request->input("tipo") == "todos") {
      // $busqueda = $busqueda->where('ventas_cab.TidCodi', '!=' , TipoDocumentoPago::NOTA_VENTA );
      $busqueda = $busqueda->whereNotIn('ventas_cab.TidCodi', [TipoDocumentoPago::NOTA_VENTA, 50]);
    } else {
      $busqueda = $busqueda->where('ventas_cab.TidCodi', '=', $request->input("tipo"));
    }

    if ($request->input("estadoAlmacen")) {
      $filter = $request->estadoAlmacen == "Pe" ? '>' : '=';
      $busqueda->where('ventas_cab.VtaSdCa', $filter, 0);
    }

    if ($request->input("local") != "todos") {
      $busqueda->where('ventas_cab.LocCodi', '=', $request->input("local"));
    }

    if (!is_null($estado)) {
      if ($estado != "todos") {
        if ($estado == "anulado") {
          $busqueda->where('ventas_cab.VtaEsta', '=', "A");
        } else {
          $busqueda->where('ventas_cab.fe_rpta', '=', $estado);
        }
      }
    }

    if (!is_null($term) && $busqueda->count()) {
      $busqueda->orderBy('ventas_cab.VtaOper', 'desc');
      $filter = true;
      $busqueda = $busqueda
        ->where('prov_clientes.PCNomb', 'LIKE', '%' . $term . '%')
        ->orWhere('prov_clientes.PCRucc', 'LIKE', '%' . $term . '%')
        ->orWhere('ventas_cab.VtaNume', 'LIKE', '%' . $term . '%')
        ->get();
    } else {
      $busqueda->orderBy('ventas_cab.VtaOper', 'desc');
    }

    $dataTable = DataTables::of($busqueda);

    if ($filter) {
      $dataTable->filter(function ($query) {
        return true;
      }, false);
    }

    return $dataTable
      ->addColumn('nro_venta', 'ventas.partials.factura.column_nro_venta')
      ->addColumn('estado', 'ventas.partials.factura.column_estado')
      ->addColumn('btn', 'ventas.partials.factura.column_actions')
      ->addColumn('alm', 'partials.column_alm')
      ->rawColumns(['nro_venta',  'estado', 'btn', 'alm'])
      ->make(true);
  }

  public function search_pendientes(Request $request)
  {
    $empresa = get_empresa();
    $busqueda = Venta::query()->with(
      ['cliente_with' => function ($q) {
        $q->where('EmpCodi', empcodi())
          ->where('TipCodi', 'C');
      }, 'forma_pago', 'vendedor', 'moneda']
    );

    $busqueda
      ->where('EmpCodi', $empresa->empcodi)
      ->where('VtaFMail', '=', "0011")
      ->where('TidCodi', '!=', '52')
      ->where('TidCodi', '!=', '50')
      ->where('contingencia', '=', '0')
      ->where('VtaEsta', '=', 'V')
      ->orderBy('VtaOper', 'desc');

    if (!$empresa->isAvailableSendTidDocument(TipoDocumentoPago::BOLETA)) {
      $busqueda->where('TidCodi', '!=', TipoDocumentoPago::BOLETA);
    }

    if ($request->tipo != 'todos') {
      $busqueda->where('TidCodi', $request->tipo);
    }

    return datatables()->of($busqueda)
      ->addColumn('nro_venta', 'ventas.partials.factura.column_nro_venta')
      ->rawColumns(['nro_venta'])
      ->toJson();
  }

  public function factura_accion($id_factura = null, $accion = "create", $ventaForNotaCredito = null)
  {
    $empcodi = empcodi();
    $user = auth()->user();
    $tprepository = new TipoPagoRepository(new TipoPago());
    $mprepository = new MedioPagoRepository(new MedioPago(), $empcodi);
    $tipoigvrepository = new TiposIGVRepository(new TipoIgv());
    $bancocuenta_repository = new BancoCuentaRepository(new BancoEmpresa, $empcodi);
    $condicion_repository = new CondicionCompraVentaRepository(new CondicionVenta(), $empcodi);
    $tipo_movimiento =  new TipoMovimiento();
    $tipo_movimiento_repository = $tipo_movimiento->repository();
    $empresa = get_empresa();
    $bancos = $bancocuenta_repository->all()->load(['cajas', 'banco']);
    $ventaForNotaCreditoData = ['serie' => '', 'numero' => ''];
    if ($ventaForNotaCredito) {
      $ventaForNotaCredito = Venta::findOrfail($ventaForNotaCredito);
      $ventaForNotaCreditoData['serie']  = $ventaForNotaCredito->VtaSeri;
      $ventaForNotaCreditoData['numero'] = $ventaForNotaCredito->VtaNumee;
    }

    $decimales = getEmpresaDecimals();

    $data = [
      'tipo_pagos' => $tprepository->all(),
      'notaCredito' => (object) $ventaForNotaCreditoData,
      'medios_pagos' => $mprepository->all()->where('uso', MedioPago::ESTADO_USO),
      'bancos' => $bancos,
      'almacenes' => $empresa->almacenes,
      'locales' => $user->locales->load('local'),
      'incluyeIgv' => (int) $empresa->incluyeIgv(),
      'igvEmpresa' => $empresa->getIgvPorc(),
      'canModifyPrecios' => (int) $user->canModifyPrecios(),
      'tipo_movimientos' => $tipo_movimiento_repository->where('TmoInSa', 'S'),
      'tipos_igvs' => $tipoigvrepository->where('gratuito_disponible', TipoIgv::GRATUITO_DISPONIBLE),
      "condicion" => $condicion_repository->find(CondicionVenta::ID_VENTA)->CcvDesc,
      "condicion_cot" => $condicion_repository->find(CondicionVenta::ID_COTIZACION)->CcvDesc,
      'decimales_dolares' => $decimales->dolares,
      'decimales_soles' => $decimales->soles,
      "verificar_deudas" => get_option('ImpSald'),
      "verificar_caja" => get_option('OpcConta'),
      "verificar_almacen" => get_option('DesAuto'),
      "descuento_defecto" => get_option('ImpDcto'),
      "inicial_focus" => $empresa->SoftEsta,
      
      EmpresaOpcion::MODULO_MANEJO_STOCK => $empresa->getDataAditional(EmpresaOpcion::MODULO_MANEJO_STOCK),

      EmpresaOpcion::MODULO_RESTRICCION_VENTA_STOCK => $empresa->getDataAditional(EmpresaOpcion::MODULO_RESTRICCION_VENTA_STOCK),

      "impresion_default" => 1,
      "formato" => $empresa->fe_formato,

      "cursor_pointer_producto" => get_option(EmpresaOpcion::CAMPO_CURSOR_PRODUCTO),
      "cursor_pointer_inicial"  => get_option(EmpresaOpcion::CAMPO_CURSOR_INICIAL),
      "ultimo_codigo" => ClienteProveedor::ultimoCodigo(),
      'ruc' => "",
    ];

    if ($accion == "create") {
      $data['tipos_clientes'] = cacheHelper('tipocliente.all');
      $data['tipos_documentos_clientes'] = cacheHelper('tipodocumento.all');
      $data['departamentos'] = cacheHelper('departamento.all');
      $data['lista_precios'] = cacheHelper('listaprecio.all');
      $data[EmpresaOpcion::MODULO_CANJE] = $empresa->getDataAditional(EmpresaOpcion::MODULO_CANJE);
      $data['grupos'] = cacheHelper('grupo.all');
      $data["empresa"] = $empresa;
      $data["create"] = true;
      $data["guia"] = new GuiaSalida;
      $data["monedas"] = cacheHelper('moneda.all');
      $data["tipos_documentos"] = SerieDocumento::getSeriesVentas(getTrueIsEmpresaNCHabit());
      $data["tipo_cambio"] = TipoCambioPrincipal::ultimo_cambio(false);
      $data["vendedores"] = $empresa->vendedores;
      $data["forma_pagos"] = $empresa->formas_pagos->load('dias');
      $data["cliente_default"] = ClienteProveedor::clienteDefault();
      $data["tipo_documento_defecto"] = get_option('CcoCodi');
      $data['id_nuevo'] = Venta::UltimoId();
      $data["create"] = 1;
      $data["tipos_notacredito"] = cacheHelper('tiponotacredito.all');
    } else {
      $venta = Venta::find($id_factura);
      $data['venta'] = $venta;
      $data['create'] = 0;
      $data['pagos'] = $venta->pagos;
      $data['has_guia_referencia'] = $venta->hasGuiaReferencia();
      $data['has_guias_asoc'] = false;

      if ($data['has_guia_referencia']) {
        $data['guiaNombre'] = $venta->getNameGuiaCorrelative();
        $data['guiaRoute'] = route('guia.edit', $venta->GuiOper);
      } else {
        $data['guias'] = $guias = $venta->guias_ventas->load('guia.cli');
        $data['guias_count'] = $guias->count();
        $data['has_guias_asoc'] = (bool) $data['guias_count'];
      }
    }

    return view('ventas.crear_modificar_factura', $data);
  }

  public function create_factura(Request $request)
  {
    $notaCredito = $request->input('notaCredito', null);
    return $this->factura_accion(null, "create", $notaCredito);
  }

  public function edit($id_factura)
  {
    ini_set('max_execution_time', '300');
    $venta = Venta::find($id_factura);
    return $this->factura_accion($id_factura, "edit");
  }

  public function show($id_factura)
  {
    ini_set('max_execution_time', '300');
    return $this->factura_accion($id_factura, "edit");
  }

  public function verify_item(VentasItemRequest $request)
  {
    $data = $request->all();
    $igvTotal = ($data['DetImpo'] / 100 * 18);
    $data['DetPvta'] = bcdiv($data['DetImpo'] - $igvTotal, 1, 2);
    return $data;
  }

  public function checkDeudas(ClienteExistsRequest $request)
  {
    $cliente = ClienteProveedor::findByRuc($request->id_cliente);
    return $cliente ? $cliente->deudas() : "false";
  }

  public function saveFactura(FacturaSaveRequest $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_VENTA'));
    $tipo_guia = $request->input('guia_tipo', 0);
    $con_productos_enviados = $tipo_guia != Venta::GUIA_ACCION_NINGUNA;
    $documento = null;
    $vtaoper = null;
    $serie = null;
    $data_impresion = null;
    $isTableVentas = $request->tipo_documento != TipoDocumentoPago::PROFORMA;
    $isFacturacion = TipoDocumentoPago::isTipoVentas($request->tipo_documento);
    $error = '';

    DB::connection('tenant')->beginTransaction();
    DB::connection()->beginTransaction();

    try {
      if ($isTableVentas) {
        $documento = Venta::createFactura($request, $con_productos_enviados, $request->total_documento);
        VentaItem::createItem($documento->VtaOper, $request->items, $con_productos_enviados, $request->totales_items, $request->placa_vehiculo);
        $vtaoper = $documento->VtaOper;
      } else {
        $documento = Cotizacion::guardarFromVenta($request->all(), $request->total_documento);
        $vtaoper = $documento->CotNume;
        CotizacionItem::guardarFromVenta($request->items, $documento, $request->totales_items);
      }

      if ($isTableVentas) {
        $documento->createFormaPago($request->pagos);
        $tipo_guia = $request->input('guia_tipo', Venta::GUIA_ACCION_NINGUNA);

        if ($tipo_guia != Venta::GUIA_ACCION_NINGUNA) {
          $rpta = $documento->createOrAssocGuia(
            $tipo_guia,
            $request->guias,
            $request->input('id_almacen', '001'),
            TipoMovimiento::DEFAULT_SALIDA,
            $request->guiasIds
          );
          if (!$rpta->success) {
            throw new Exception(sprintf("Error Creando Guia: %s", $rpta->data), 1);
          }
        }

        $empresa = get_empresa();
        $formato = $request->input('formato_impresion', 'a4');
        $save = $formato == PDFPlantilla::FORMATO_A4;
        $documento->fresh()->saveXML();
        $serie = $request->serie;
        $result = $documento->generatePDF($formato, $save, true, $serie->impresion_directa);
      }
    else {
      $result = $documento->generatePDF(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR, get_empresa()->hasImpresionIGV(), true, true);
      }

      $documento->updateSeries();


      if ($request->canje) {
        $documento->updateNotaVentaByCanje($request->canjeQuery);
      }
      DB::connection('tenant')->commit();
      DB::connection()->commit();
    } catch (Throwable  $e) {
      $error = substr($e->getMessage(), 0, 150);
      DB::connection('tenant')->rollBack();
      DB::connection()->rollBack();
      return response()->json(['message' => 'Error al guardar el documento: ' . $error], 500);
    }

    if ($isTableVentas && $isFacturacion) {
      $empresa->sumarConsumo(Caracteristica::COMPROBANTES);
    }

    // $url = str_replace('\\', '/', asset($result));
    $url = asset($result);
    $needFactura =  $isFacturacion ? $documento->needFactura()  : false;
    $needPago = $request->canje ? false : ($isTableVentas ?  $documento->needPago() : false);
    $data = [
      'codigo_venta' => $vtaoper,
      'nro_documento' => '',
      'url' =>  $url,
      'url_pago' => route('ventas.check_pago', ['id_factura' => $vtaoper]),
      'guia' => [],
      'need_factura' => $needFactura,
      'need_pago' => $needPago,
      'imprecion_data' => [
        'impresion_directa' => $request->serie->impresion_directa,
        'cantidad_copias' => $request->serie->cantidad_copias,
        'nombre_impresora' => $request->serie->nombre_impresora,
        'data_impresion' => $data_impresion
      ],
    ];

    return $data;
  }

  public function imprimirFactura($id_factura, $formato = "a4", $download = 0)
  {
    ob_end_clean();
    ob_start();

    $venta = Venta::find($id_factura);
    $plantilla_data = $venta->getPlantilla($formato);
    $namePDF = $venta->nameFile('.pdf');
    // ------------------------------------------------------------------------
    $pathTemp = file_build_path('temp', $namePDF);
    $venta = Venta::find($id_factura);
    $empresa = get_empresa();
    $fileHelper = fileHelper($empresa->ruc());

    if ($plantilla_data->isFormatoA4()) {
      // Verificar si ha sido creado su archivo, si es asi usarlo, si no crearlo
      if ($fileHelper->pdfExist($namePDF)) {
        \File::put(public_path($pathTemp), $fileHelper->getPdf($namePDF));
      } else {
        $pathTemp = $venta->generatePDF($formato, true, true, false, PDFGenerator::HTMLGENERATOR);
      }
    } else {
      $generator = $formato == PDFPlantilla::FORMATO_A5 ? PDFGenerator::HTMLGENERATOR : PDFGenerator::HTMLGENERATOR;
      $pathTemp = $venta->generatePDF($formato, false, true, false, $generator);
    }

    // Si es mobil, descargar
    if ($download == "1" || isMobile()) {
      $realPath = file_build_path($pathTemp);
      return response()->download($realPath, $namePDF);
    }

    $path = asset($pathTemp);
    $pathJs = str_replace('\\', '/', $path);
    return view('ventas.pdf_test', ['path' => $path, 'pathJS' => $pathJs, 'nameFile' => $namePDF]);
  }


  public function imprimirFactura_($id_factura, $formato = "a4")
  {
    $venta = Venta::find($id_factura);

    if ($formato == "new") {
      $data = $venta->dataPdf(Venta::FORMATO_TICKET);
      $pdf = new PDFGenerator(view('pdf.documents.default.documento3', $data), PDFGenerator::HTMLGENERATOR);
      $pdf->generator->setGlobalOptions([
        'no-outline',
        'page-width' => '8cm',
        'page-height' => '29.7cm',
        'margin-top' => '0in',
        'margin-right' => '0in',
        'margin-bottom' => '0in',
        'margin-left' => '0in',
        'orientation' => 'portrait',
      ]);

      $pdf->generator->generate();

      return view('pdf.documents.default.documento3', $data);
    } else {
      $namePdf = $venta->nameFile('.pdf');
      $pathTempAbsolute = "temp/{$namePdf}";
      $pathTemp = asset($pathTempAbsolute);
      $is_ticket = $formato == 'ticket';


      if ($is_ticket) {
        $data = $venta->dataPdf(Venta::FORMATO_TICKET);
        $pdf = \PDF::loadView('ventas.pdf_ticket', $data);
        $pdf->setPaper([0, 0, 205, 1000]);
        $pdf->save($pathTempAbsolute);
      } else {
        if ($this->fileHelper->pdfExist($namePdf) && $formato == Venta::FORMATO_A4) {
          $content = $this->fileHelper->getPdf($namePdf);
          \File::put(public_path($pathTempAbsolute), $content);
        } else {
          $pathTemp = $venta->savePdf($formato);
        }
      }

      if (isMobile()) {
        $realPath = file_build_path(public_path(), 'temp', "{$namePdf}");
        return response()->download($realPath, $namePdf);
      }

      return view('ventas.pdf_test', ['path' => $pathTemp]);
    }
  }

  public function verXml($id_factura)
  {
    ob_end_clean();
    ob_start();

    $venta = Venta::find($id_factura);
    $empresa = $venta->empresa;
    $fileHelper = fileHelper($empresa->ruc());
    $fileName = $venta->nameFile('.xml');

    if ($fileHelper->xmlExist($fileName)) {
      $content = $fileHelper->getEnvio($fileName);
      $path = $fileHelper->saveTemp($content, $fileName);
      return response()->download($path, $fileName, [
        'Content-Type: text/xml',
      ]);
    }

    notificacion('', 'No se encuentra este recurso', 'error');
    return redirect()->back();
  }

  public function verCdr($id_factura)
  {
    ob_end_clean();
    ob_start();
    $venta = Venta::find($id_factura);
    $nameCdr = $venta->nameCdr('.zip');
    $fileHelper = fileHelper(get_empresa()->ruc());
    if ($fileHelper->cdrExist($nameCdr)) {
      $content = $fileHelper->getCdr($nameCdr);
      $path = $fileHelper->saveTemp($content, $nameCdr);
      return response()->download($path);
    }

    noti()->error('No se encuentra este recurso');
    return back();
  }

  public function delete(VentaDeleteRequest $request)
  {
    $venta = Venta::find($request->id_factura);
    DB::beginTransaction();
    try {
      $venta->deleteComplete();
      DB::commit();
      $success = true;
    } catch (\Exception $e) {
      $success = false;
      $error = $e->getMessage();
      DB::rollback();
      return response()->json('Error al guardar el documento ' . $error, 500);
    }
    return "success";
  }

  public function pendientes($tipo = false)
  {
    return view('ventas.pendientes', [
      'tipo' => $tipo,
      'hoy' => null,
    ]);
  }

  public function verificar_ticket(Request $request)
  {
    $venta = Venta::find($request->id_factura);
    $resumen = $venta->por_enviar()->resumen;
    $nameFile = $resumen->nameFile(true, '.zip');
    DB::beginTransaction();
    try {
      $data =  Sunat::verificarTicket($resumen->DocTicket, $nameFile);
      DB::commit();
      $success = true;
    } catch (\Exception $e) {
      $success = false;
      $error = $e->getMessage();
      DB::rollback();
      return response()->json('Error al verificar ticket ' . $error, 500);
    }
    if ($success) {
      if ($data['status']) {
        $resumen->successValidacion($data);
        return $data;
      } else {
        $errorCode = (int) $data['code'];
        $error_description = ErrorSunat::findOrfail($errorCode)->nombre;
        return response()->json(['message' => $error_description], 400);
      }
    }
  }

  public function getInformacion(Request $request, $id)
  {
    $venta = Venta::findOrfail($id);
    return view('ventas.partials.informacion.informacion', [
      'venta' => $venta,
      'formato' => get_empresa()->fe_formato
    ]);
  }

  public function getFiles(Request $request, $id)
  {
    ob_end_clean();
    ob_start();

    $venta = Venta::findOrfail($id);
    $response = $venta->getFiles();

    if ($response['success']) {
      return response()->download($response['path'], $response['file_name'], []);
    } else {
      noti()->warning("No se encontrarón archivos el documento {$venta->VtaNumee}");
      return back();
    }
  }

  public function  anularNotaVenta(AnularNotaVentaRequest $request, $id)
  {
    $this->authorize(p_name('A_ANULAR', 'R_VENTA'));

    $venta = Venta::find($id);
    $venta->updateStatusCode(StatusCode::EXITO_0003['code']);
    $venta->saveStatus0003();
    $venta->anularPago();

    return response()->json(['message' => 'Se ha anulado exitosamente']);
  }


  public function searchDocumentoAnticipo(Request $request)
  {
    $ventas = Venta::with('cliente')->where('VtaNumee', 'LIKE', '%' . $request->data)
      ->where('PCCodi', $request->client_id)
      ->where('TidCodi', TipoDocumentoPago::FACTURA)
      ->where('VtaEsta', 'V')
      ->take(10)
      ->get();

    $busqueda = DB::connection('tenant')->table('ventas_cab')
      ->join('prov_clientes', function ($join) {
        $join
          ->on('prov_clientes.PCCodi', '=', 'ventas_cab.PCCodi')
          ->on('prov_clientes.EmpCodi', '=', 'ventas_cab.EmpCodi')
          ->where('prov_clientes.TipCodi', '=', 'C');
      })
      ->join('moneda', 'moneda.moncodi', '=', 'ventas_cab.MonCodi')
      ->where('ventas_cab.EmpCodi', '=', empcodi())
      ->select(
        'ventas_cab.VtaOper',
        'ventas_cab.TidCodi',
        'ventas_cab.VtaNume',
        'ventas_cab.VtaSeri',
        'ventas_cab.VtaImpo',
        'ventas_cab.VtaFvta',
        'prov_clientes.PCNomb',
        'prov_clientes.PCRucc',
        'moneda.monabre'
      );

    $data = [];

    if ($ventas) {
      foreach ($ventas as $venta) {
        $data_ = [
          'total' => $venta->Vtabase,
          'moneda' => $venta->MonCodi,
        ];

        $data[] = [
          'id' => $venta->VtaOper,
          'text' => sprintf(
            '%s (%s) (%s) (%s)',
            TipoDocumentoPago::getNombreDocumento($venta->TidCodi),
            $venta->VtaNume,
            $venta->VtaFvta,
            $venta->Vtabase,
          ),
          'data' => $data_
        ];
      }
    }

    return response()->json($data);
  }

  public function accionValidacion($id)
  {
    $doc = Venta::find($id);
    if ($doc->isBoleta()) {
      $envioDirecto = get_empresa()->envioBoleta();
      if ($doc->isPendiente()) {
        if ($envioDirecto) {
          notificacion('Se requiere Acciòn', "Seleccione y envie el documento {$doc->VtaNume}", 'success', [
            'N_hideAfter' => 9999999999,
            'N_showHideTransition' => 99999999
          ]);
          return redirect()->route('ventas.pendientes');
        } else {
          $detalle = $doc->resumenDetalle();
          if ($detalle) {
            $message = $detalle->resumen->hasTicket() ? 'Por favor validar ticket del resumen' : 'Enviar Resumen de validaciòn a la sunat';
            notificacion('Se requiere Acciòn', $message);
            return redirect()->route('boletas.agregar_boleta', ['numoper' => $detalle->numoper, 'docnume' => $detalle->docNume]);
          } else {
            notificacion('Envie su resumen', "Cree y envie el resumen de boleta para el documento {$doc->VtaNume}", 'success');
            return redirect()->route('boletas.agregar_boleta');
          }
        }
      }

      $detalle = $doc->detalle_anulacion();
      if ($detalle) {
        $message = $detalle->resumen->hasTicket() ? 'Por favor validar ticket del resumen de anulaciòn' : 'Enviar el Resumen de anulaciòn a la sunat';
        notificacion('Se requiere Acciòn', $message);
        return redirect()->route('boletas.agregar_boleta', ['numoper' => $detalle->numoper, 'docnume' => $detalle->docNume]);
      }
    }

    // Si es factura
    else {
      if ($doc->isPendiente()) {
        notificacion('Se requiere Acciòn', "Seleccione y envie el documento {$doc->VtaNume}", 'success', ['N_hideAfter' => 9999999999]);
        return redirect()->route('ventas.pendientes');
      }

      $detalle = $doc->detalle_anulacion();

      if ($detalle) {
        $message = $detalle->resumen->hasTicket() ? 'Por favor validar ticket del resumen de anulaciòn' : 'Enviar el Resumen de anulaciòn a la sunat';
        noti()->success('Se requiere Acciòn', $message);
        return redirect()->route('boletas.agregar_boleta', ['numoper' => $detalle->numoper, 'docnume' => $detalle->docNume]);
      }
    }
  }

  public function recursoAnulacion($id, $tipo = "pdf")
  {
    $doc = Venta::find($id);
    if ($doc->isAnulada()) {
      $detalle = $doc->detalle_anulacion();
      return redirect()->route('boletas.resource', [
        'id_resumen' => $detalle->numoper,
        'docnume' => $detalle->docNume,
        'tipo' => $tipo,
      ]);
    } else {
      noti()->success('Se requiere Acciòn');
      return back();
    }
  }

  public function showApi($id, $notaCredito = 1)
  {
    $documento = Venta::findOrfail($id);

    $data = $notaCredito ? $documento->dataForNotaCredito() : $documento->dataForNotaDebito();

    return response()->json($data);
  }

  public function createNC(VentaCreateNotaCreditoRequest $request, $id)
  {
    $resp = $request->documento->createNC($request->allWithAditional());
    $code = $resp['success'] ? 200 : 400;
    return response()->json([
      'success' => $resp['success'],
      'message' => $resp['error']
    ], $code);
  }

  public function  createND(VentaCreateNotaDebitoRequest $request, $id)
  {
    $resp = $request->documento->createND($request->allWithAditional());
    $code = $resp['success'] ? 200 : 400;

    return response()->json([
      'success' => $resp['success'],
      'message' => $resp['error']
    ], $code);
  }
}
