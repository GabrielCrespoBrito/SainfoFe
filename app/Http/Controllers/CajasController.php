<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Venta;
use App\Compra;
use App\Moneda;
use App\Control;
use App\TipoPago;
use App\CajaDetalle;
use App\PDFPlantilla;
use App\Mail\MailDeudas;
use App\ClienteProveedor;
use App\Http\Controllers\Caja\ReporteSimplificadoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MedioPago\MedioPago;
use App\Http\Requests\CerrarRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AperturarRequest;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Requests\BorrarCajaRequest;
use App\Http\Requests\CajaEgresoRequest;
use App\Repositories\TipoPagoRepository;
use App\Http\Requests\CajaIngresoRequest;
use App\Repositories\MedioPagoRepository;
use App\Jobs\Reporte\ReporteCuentaPorCobrar;
use App\Http\Requests\CajaEgresoStoreRequest;
use App\Http\Requests\ReaperturarCajaRequest;
use App\Http\Requests\CajaDetalleDeleteRequest;
use App\Http\Requests\CajaDineroAperturaRequest;
use App\Util\ExcellGenerator\CuentaPorPagarExcell;
use App\Http\Requests\CajaDetalle\CajaDetalleIngresoUpdateRequest;
use App\Util\ExcellGenerator\CajaMovimientoExcell;

class CajasController extends Controller
{
  use ReporteSimplificadoTrait;

  public function __construct()
  {
    $this->middleware(p_midd('A_INDEX', 'R_CAJA'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_CAJA'))->only('reaperturar', 'dinero_apertura');
    $this->middleware(p_midd('A_MOVIMIENTOS', 'R_CAJA'))->only('movimientos');
    $this->middleware(p_midd('A_REPORTE_COMPRA_VENTA', 'R_CAJA'))->only('reporteVenta');
    $this->middleware(p_midd('A_REPORTE_INGRESO_EGRESO', 'R_CAJA'))->only('reporteDetallado');
  }

  public function index()
  {
    $need_apertura = Caja::hasAperturada();
    $empresa = get_empresa();
    $locales = $empresa->almacenes_elegibles();
    $usuarios = $empresa->empresa_usuarios;
    $caja_local = $empresa->isTipoCajaLocal();
    $last_id = $empresa->ultima_caja();
    return view('cajas.index', compact('need_apertura', 'locales', 'usuarios', 'last_id', 'caja_local'));
  }


  public function search(Request $request)
  {
    $busqueda = Caja::query();
    $busqueda
      ->where('MesCodi', $request->fecha)
      ->where('LocCodi', $request->local)
      ->where('CueCodi', Caja::TIPOCAJA)
      ->where('EmpCodi', empcodi())
      ->orderBy('CajFech', 'desc');

    if (!get_empresa()->isTipoCajaLocal()) {
      $busqueda->where('User_Crea', $request->usuario);
    }

    return datatables()->of($busqueda)
      ->addColumn('column_link', 'banco.partials.column_link')
      ->rawColumns(['column_link'])
      ->toJson();
  }

  public function resumen($id_caja)
  {
    // _dd("aja");
    // exit();
    $tps = (new TipoPagoRepository(new TipoPago()))->all();
    
    // ->where('TdoBanc', '0');
    $caja = Caja::find($id_caja);
    $caja->calculateSaldo();
    $data = $caja->data_reportes();
    $data['tipos_pagos'] = $tps;
    return view('reportes.caja_resumen', $data);
  }


  public function aperturar(AperturarRequest $request)
  {
    Caja::Aperturar($request);
  }

  public function reaperturar(ReaperturarCajaRequest $request)
  {
    $caja = Caja::find($request->id_caja);
    $caja->reaperturar();
    return "true";
  }

  public function cerrar(CerrarRequest $request)
  {
    $caja = Caja::find($request->id_caja);
    $caja->cerrar();
    return "true";
  }

  public function searchMovimientos(Request $request, $caja, $tipo_movimiento)
  {
    $tipo_movimiento = $tipo_movimiento == "ingresos" ? Caja::INGRESO : Caja::EGRESO;
    $busqueda = CajaDetalle::where('CajNume', $caja)->where('ANULADO', $tipo_movimiento);

    return DataTables::of($busqueda)
      ->addColumn('documento', 'cajas.partials.movimientos.column_documento')
      ->addColumn('monto_dolares', 'cajas.partials.movimientos.column_monto_dolares')
      ->addColumn('monto_soles', 'cajas.partials.movimientos.column_monto_soles')
      ->addColumn('acciones', 'partials.column_accion_model')
      ->rawColumns(['documento',  'acciones', 'monto_soles', 'monto_dolares'])
      ->make(true);
  }

  public function movimientos($id_caja, $tipo_movimiento = "ingresos")
  {
    $is_ingreso = $tipo_movimiento ==  "ingresos";
    $letter_mov = $is_ingreso ? "I" : "S";

    $caja = Caja::find($id_caja);
    $movimientos = Caja::detallesByTipo($id_caja, $letter_mov);
    $empresa = get_empresa();
    $nombreCaja = $caja->nombre();
    $campo_soles = $is_ingreso ? "CANINGS" : "CANEGRS";
    $campo_dolar = $is_ingreso ? "CANINGD" : "CANEGRD";
    $total_soles =  decimal($movimientos->sum($campo_soles));
    $total_dolar = decimal($movimientos->sum($campo_dolar));
    $personales = $empresa->personal;
    $cajas = $empresa->cajas_aperturadas($id_caja);
    $cuentas = $empresa->bancos;

    // $cuentas = Caja::  $empresa->bancos;
    $motivos = $is_ingreso ? $empresa->motivosIngresos : $empresa->motivosEgresos;

    return view('cajas.movimientos', compact('caja', 'is_ingreso', 'movimientos', 'tipo_movimiento', 'cajas',  'motivos', 'total_soles', 'total_dolar', 'personales', 'cuentas', 'nombreCaja'));
  }

  public function egresos(CerrarRequest $request)
  {
    return back();
  }

  function consultar_movimiento($id_caja, $id_tipomovimiento, Request $request)
  {
    $caja = Caja::find($id_caja);

    $movimiento =
      $caja->detalles
      ->where('Id', $request->id_movimiento)
      ->first();

    $movimiento = $movimiento->toArray();
    return $movimiento;
  }


  public function create_movimiento(Request $request)
  {
    return $request->all();
  }

  // public function dinero_apertura( $id_caja , CajaDineroAperturaRequest $request )
  public function dinero_apertura($id_caja, CajaDineroAperturaRequest $request)
  {
    $caja = Caja::find($request->id_caja);

    $detalle_apertura = $caja->detalles->where('CtoCodi', Control::CAJA)->first();
    $detalle_apertura->update($request->all());

    $caja->calculateSaldo();


    return response()->json([
      'message' => 'Acción Exitosa',
      'soles' => fixedValue($request->CANINGS),
      'dolar' => fixedValue($request->CANINGD)
    ]);
    // ---------------------------------------------------------     
  }

  public function borrar(BorrarCajaRequest $request)
  {
    $caja = Caja::find($request->id_caja);
    $caja->eliminar();
  }

  public function movimiento_aperturar($id_caja, Request $request)
  {
  }

  public function egresos_create($cajaNume, CajaEgresoStoreRequest $request)
  {
    $this->authorize(p_name('A_CREATE_MOVIMIENTOS', 'R_CAJA'));

    $caja = Caja::find($cajaNume);
    return CajaDetalle::registrarEgresoCaja($request->all(), $caja);
  }

  public function egresos_edit($cajaNume, CajaEgresoRequest $request)
  {
    $this->authorize(p_name('A_EDIT_MOVIMIENTOS', 'R_CAJA'));

    $caja = Caja::find($cajaNume);
    $caja_detalle = CajaDetalle::find($request->id_movimiento);
    $caja_detalle->deleteFull();
    return CajaDetalle::registrarEgresoCaja($request->all(), $caja, true);
  }


  public function ingresos_create(CajaIngresoRequest $request, $cajaNume)
  {
    $this->authorize(p_name('A_CREATE_MOVIMIENTOS', 'R_CAJA'));

    $caja = Caja::find($cajaNume);

    CajaDetalle::registrarIngresoCaja($request->all(), $cajaNume);

    return response()->json(['message' => 'Se ha creado exitosamente el registro']);
  }

  public function ingresos_update(CajaDetalleIngresoUpdateRequest $request, $id_caja)
  {
    $this->authorize(p_name('A_EDIT_MOVIMIENTOS', 'R_CAJA'));

    $caja_detalle = CajaDetalle::find($request->id_movimiento);
    $caja_detalle->MonCodi = $request->moneda;
    $caja_detalle->CANINGS = $request->moneda == "01" ? $request->monto : 0;
    $caja_detalle->CANINGD = $request->moneda != "01" ? $request->monto : 0;
    $caja_detalle->MocFech = $request->fecha;
    $caja_detalle->MocNomb = $request->nombre;
    $caja_detalle->MOTIVO  = $request->nombre;
    $caja_detalle->AUTORIZA = $request->autoriza;
    $caja_detalle->EgrIng = $request->motivo;
    $caja_detalle->OTRODOC = $request->otro_doc;
    $caja_detalle->save();

    return response()->json(['messge' => 'Ingreso actualizado correctamente']);
  }

  public function borrar_movimiento(CajaDetalleDeleteRequest $request)
  {
    $caja_detalle = CajaDetalle::find($request->id_movimiento);
    $caja = $caja_detalle->caja;


    $caja_detalle->delete();
    return response()->json(['message' => 'Eliminado movimiento de caja exitosamente'], 200);
  }

  public function reporte(Request $request)
  {

    $caja = Caja::find($request->id_caja);
    $nameFile = str_random(10) . '.pdf';
    $tempFileName = file_build_path('temp', $nameFile);
    $pathToFile = public_path($tempFileName);

    $data = $caja->data_reportes();
    $data['empresa'] = get_empresa()->toArray();

    $pdf = \PDF::loadView('cajas.resumen_pdf', $data);
    $pdf->save($pathToFile);
    // 2022-08-02 10:05
    $contenido = base64_encode(file_get_contents($pathToFile));
    return ['contenido' =>  $contenido, 'nombre' => 'Resumen_caja.pdf'];
  }


  public function reporteDetallado($id_caja, $tipo = "pdf")
  {
    ini_set('max_execute_time', 180);
    ob_end_clean();

    $caja = Caja::with(['detalles.pago'])->find($id_caja);
    $titulo = $caja->isTipoBanco() ?  sprintf('REPORTE DE CUENTA %s', $caja->bancoCuenta->getNombreFull()) : 'REPORTE DE CAJA CHICA';

    if ($tipo == "pdf") {
      $nameFile = str_random(10) . '.pdf';
      $data = $caja->dataReporte();
      $data['titulo'] = $titulo;
      $data['empresa'] = get_empresa()->toArray();

      $generator = new PDFGenerator(
        view('cajas.resumen_detallado', $data),
        PDFGenerator::HTMLGENERATOR
      );
      $generator->generator->setGlobalOptions(PDFGenerator::getSetting(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR));
      return $generator->generate();
    }
    // ------------------------------------FuckThatNigger-----------------------------------

    $empresa = get_empresa();
    $data = [
      'data' => $caja->dataReporte(),
      'titulo' => $titulo,
      'nombre_empresa' => $empresa->nombre(),
      'usuario' => $caja->User_Crea,
      'caja' => $caja->CajNume,
      'fecha_apertura' => $caja->CajFech,
      'fecha_cierre' => $caja->CajFecC,
      'estado' => $caja->estado,
    ];

    $excell = new CajaMovimientoExcell($data);

    $info = $excell
      ->generate()
      ->store();

    return response()->download($info['full'], $info['file']);
  }

  public function cuentas($tipo, $id_cliente = null)
  {
    $isPorPagar = $tipo === 'por_pagar';

    $isPorPagar ?
      $this->authorize(p_name('A_CUENTASPORPAGAR', 'R_CAJA'))  :
      $this->authorize(p_name('A_CUENTASPORCOBRAR', 'R_CAJA'));

    $empresa = get_empresa();
    $users = $empresa->users;
    $title = $isPorPagar ? 'Cuentas por Pagar' : 'Cuentas por Cobrar';
    $title_pagina = $isPorPagar ? 'Cuentas por Pagar' : 'Cuentas por Cobrar';
    $entidad = $isPorPagar ? 'Proveedores' : 'Clientes';
    $type = $isPorPagar ? 'P' : 'C';
    $tiposPagos = (new MedioPagoRepository(new MedioPago(), $empresa->empcodi))->all();
    $tiposPagos = $isPorPagar ? $tiposPagos : $tiposPagos->where('uso', MedioPago::ESTADO_USO);
    $type_pago = $isPorPagar ? 'compra' : 'venta';
    $url_search  = route('cajas.cuentasporpagar_search', $tipo);
    $compra  = new Compra;
    $venta  = new Venta;
    $isPorPagar = (int) $isPorPagar;
    return view('cajas.cuentasporpagar', compact('users', 'title', 'title_pagina', 'entidad', 'type', 'tipo', 'url_search', 'type_pago', 'compra', 'venta', 'isPorPagar', 'tiposPagos'));
  }

  public function getTotals(Request $request, $isPorPagar)
  {
    $isPorPagar = (bool) $isPorPagar;
    $tipoCliente = $isPorPagar ? ClienteProveedor::TIPO_PROVEEDOR : ClienteProveedor::TIPO_CLIENTE;
    $cliente = $request->cliente ? ClienteProveedor::findByTipo($request->cliente, $tipoCliente)->PCCodi : null;
    // $cliente = $request->cliente ? ClienteProveedor::findByTipo($request->cliente, $tipoCliente)->PCCodi : null;
    $campoTotal = $isPorPagar ? 'CpaSald' : 'VtaSald';
    $campoMoneda = $isPorPagar ? 'moncodi' : 'MonCodi';
    $busqueda = $isPorPagar ?
      $this->search_porpagar($request->user, $request->tipo, $cliente) :
      $this->search_porcobrar($request->user, $request->tipo, $cliente);

    $busquedaDolar = clone $busqueda;
    $totalSol = $busqueda->where($campoMoneda, Moneda::SOL_ID)->sum($campoTotal);
    $totalUSD = $busquedaDolar->where($campoMoneda, Moneda::DOLAR_ID)->sum($campoTotal);
    
    return [
      'totalSol' => deci($totalSol),
      'totalUSD' => deci($totalUSD),
    ];
  }

  public function search_porcobrar($user = "todos", $tipo = "pendientes", $cliente = null)
  {
    $busqueda = Venta::query()->with(['moneda', 'cliente_with' => function ($q) {
      $q->where('TipCodi', ClienteProveedor::TIPO_CLIENTE );
    }])->where('TipoOper', '!=', Venta::TIPO_CANJEADOR)
      ->orderByDesc('VtaFvta');

    if ($user != "todos") {
      $busqueda->where('UsuCodi', $user);
    }

    if ($tipo != "historico") {
      $busqueda->where('VtaSald', '>', 0);
    }

    if ($tipo == "vencidas") {
      $busqueda->where('VtaFVen', '<', hoy());
    }

    if (is_numeric($cliente)) {
      $busqueda->where('PCCodi', $cliente);
    }

    return $busqueda;
  }

  // public function search_porpagar($request)
  public function search_porpagar($user = "todos", $tipo = "pendientes", $cliente = null)
  {
    $busqueda = Compra::query()->with(['moneda', 'cliente_with' => function ($q) {
      $q
        ->where('EmpCodi', empcodi())
        ->where('TipCodi', 'P')
        ->get();
    }]);

    if ($user != "todos") {
      $busqueda->where('usuCodi', $user);
    }

    if ($tipo != "historico") {
      $busqueda->where('CpaSald', '>', 0);
    }

    if ($tipo == "vencidas") {
      $busqueda->where('CpaFCpa', '<', hoy());
    }

    if ($cliente) {
      $busqueda->where('PCcodi', $cliente);
    }

    // return $busqueda->distinct('CpaOper');
    return $busqueda;
  }

  public function cuentasporpagar_search(Request $request, $tipo)
  {
    // $busqueda = $tipo == "por_pagar" ? $this->search_porpagar($request) : $this->search_porcobrar($request);
    $busqueda = $tipo == "por_pagar" ?
      $this->search_porpagar($request->user, $request->tipo, $request->cliente) :
      $this->search_porcobrar($request->user, $request->tipo, $request->cliente);


    return datatables()->of($busqueda)
      ->toJson();
  }

  public function cuentasporpagar_pdf(Request $request, $tipo)
  {
    $isPorPagar = $tipo == "por_pagar";
    $title = $isPorPagar ? 'Reporte - Cuentas Por Pagar' : 'Reporte - Cuentas Por Cobrar';
    $isPDF = $request->input('formato', 'pdf') == "pdf";
    $agrupacionByCliente = $request->agrupacion == "cliente";

    $docs = $isPorPagar ?
      $this->search_porpagar($request->user, $request->tipo, $request->cliente)->get() :
      $this->search_porcobrar($request->user, $request->tipo, $request->cliente)->get();

    $campoTotal = $isPorPagar ? 'CpaSald' : 'VtaSald';

    if ($agrupacionByCliente) {
      $pccodi = $isPorPagar ? 'PCcodi' : 'PCCodi';
      $docs = $docs->groupBy($pccodi);
    }

    $columns = [
      'por_pagar' => [
        'moncodi' => 'moncodi',
        'fecha' => 'CpaFCpa',
        'fecha_ven' => 'CpaFven',
        'id' => 'CpaOper',
        'tidcodi' => 'TidCodi',
        'nume' => 'CpaNume',
        'saldo' => 'CpaSald',
        'total' => 'Cpatota',
        'pago' => 'CpaPago',
      ],
      'por_cobrar' => [
        'moncodi' => 'MonCodi',
        'fecha' => 'VtaFvta',
        'fecha_ven' => 'VtaFVen',
        'id' => 'VtaOper',
        'tidcodi' => 'TidCodi',
        'nume' => 'VtaNume',
        'saldo' => 'VtaSald',
        'total' => 'VtaTota',
        'pago' => 'VtaPago',
      ],
    ];

    $campos = $columns[$tipo];
    $reporter = new ReporteCuentaPorCobrar($docs, $agrupacionByCliente, $campos);

    $data = [
      'data' => $reporter->getData(),
      'agrupacion' => $request->agrupacion,
      'title' => $title,
      'campos' => $campos,
      'campoTotal' => $campoTotal,
      'isPorPagar' => $isPorPagar,
    ];

    if ($isPDF) {
      // $pdf = \PDF::loadView('cajas.cuentasporpagarpdf', $data);
      // return $pdf->stream();

      $generator = new PDFGenerator(view('cajas.cuentasporpagarpdf', $data), PDFGenerator::HTMLGENERATOR);
      return $generator->generate();

    } else {

      ob_end_clean();
      $empresa = get_empresa();
      $nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
      $excellExport = new CuentaPorPagarExcell($data, $title, $nombreEmpresa);

      $info = $excellExport
        ->generate()
        ->store();

      return response()->download($info['full'], $info['file']);
    }
  }

  public function deudasMail(Request $request)
  {
    $busqueda = $this->search_porcobrar(
      $request->input('user'),
      $request->input('tipo'),
      $request->input('ruc')
    );
    $ventas = $busqueda->get()->groupBy('PCCodi');
    $campos =
      [
        'moncodi' => 'MonCodi',
        'fecha' => 'VtaFvta',
        'fecha_ven' => 'VtaFVen',
        'id' => 'VtaOper',
        'tidcodi' => 'TidCodi',
        'nume' => 'VtaNume',
        'saldo' => 'VtaSald',
        'total' => 'VtaTota',
        'pago' => 'VtaPago',
      ];

    $data_info = [
      'ventas' => $ventas,
      'docs' => $ventas,
      'agrupacion' => 'cliente',
      'info' => $request->all(),
      'title' => 'Reporte - Cuentas Por Cobrar',
      'campos' => $campos
    ];

    $agrupacionByCliente = $data_info['agrupacion'] === "cliente";
    $reporter = new ReporteCuentaPorCobrar($ventas, $agrupacionByCliente, $campos);
    $reporter->handle();
    $data = $reporter->getData();

    $title = $data_info['title'];
    $agrupacion = $data_info['agrupacion'];
    $info = $data_info['info'];
    $campos = $data_info['campos'];
    $docs = $data_info['ventas'];

    $generator = new PDFGenerator(view('cajas.cuentasporpagarpdf', compact('data', 'title', 'agrupacion', 'info', 'campos')), PDFGenerator::HTMLGENERATOR);
    $generator->generator->setGlobalOptions(PDFGenerator::getSetting(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR, false));

    $path = FileHelper()->saveTemp($generator->generator->toString(), time() . '.pdf');
    $data_info['documento'] = $path;
    Mail::to($request->email)->send(new MailDeudas($data_info));
    return "true";
  }


  public function reporteCompra($caja_id)
  {
    $caja = Caja::find($caja_id);
    $view = view('cajas.reporte_venta');
    $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
    return $generator->generate();
  }

  public function reporteVenta($caja_id, $tipo, $agrupacion = "tipo_documento")
  {
    $idModel = $tipo == 'ventas' ? 'VtaOper' : 'CpaOper';
    $tipoCliente = $tipo == 'ventas' ? ClienteProveedor::TIPO_CLIENTE :  ClienteProveedor::TIPO_PROVEEDOR;
    $relations  = [$tipo  => function ($query) use ($idModel) {
      $query
        ->whereIn('TidCodi', ['01', '03', '07', '08', '52'])
        ->orderBy($idModel, 'asc')
        ->orderBy('TidCodi', 'asc');
    },  $tipo . '.cliente_with' => function ($q) use ($tipoCliente) {
      $q->where('TipCodi', $tipoCliente);
    }];

    $caja =  Caja::with($relations)->findOrfail($caja_id);
    $data =  $caja->getDataReporteCompraVenta($tipo);


    $view = view('cajas.reporte_venta', $data);
    $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
    return $generator->generate();
  }

}
