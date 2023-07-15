<?php

namespace App\Http\Controllers\Guia;

use App\Venta;
use App\Compra;
use App\Moneda;
use App\TipoPago;
use App\Vehiculo;
use App\Vendedor;
use App\FormaPago;
use App\GuiaSalida;
use App\ListaPrecio;
use App\TipoCliente;
use App\Departamento;
use App\TipoDocumento;
use App\Transportista;
use App\CondicionVenta;
use App\GuiaSalidaItem;
use App\MotivoTraslado;
use App\SerieDocumento;
use App\TipoMovimiento;
use App\TipoNotaCredito;
use App\ClienteProveedor;
use App\EmpresaOpcion;
use App\Models\Guia\Guia;
use App\TipoCambioMoneda;
use App\EmpresaTransporte;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Guia\GuiaGenerarDocRequest;
use App\Http\Requests\Guia\GuiaDespachoIngresoRequest;
use App\Http\Requests\GuiaIngresoFromCompraRequest;

class GuiaController extends Controller
{
  public $tipo;
  public $model;
  public function searchJson(Request $request)
  {
    $term = $request->input('data');
    $data = [];

    if ($term) {
      $guias = $this->model->with(['cli' => function ($query) {
        $query->where('TipCodi', 'C');
      }, 'almacen'])
        ->tipo(Guia::SALIDA)
        ->formato(Guia::CON_FORMATO)
        ->numeracion($term)
        ->take(10)
        ->get();

      foreach ($guias as $guia) {
        $data[] = [
          'text' => sprintf('%s | %s | %s %s', $guia->numero(), $guia->GuiFemi, $guia->cli->PCRucc, $guia->cli->PCNomb),
          'id' => $guia->GuiOper,
        ];
      }
    }

    return response()->json($data, 200);
  }

  public function search(Request $request)
  {
    $term = $request->input('search')['value'];
    $filtro = $request->input('filtro', 'codigo');

    $mes = $request->mes;
    $formato = $request->formato;
    $local = $request->local;
    $tipo = $this->tipo;
    $tipoDoc = $request->input('tipo_documento');
    $tipoEntidad =  $tipo == GuiaSalida::INGRESO ? 'P' : 'C';

    $busqueda =
      $this->model
      ->with(['cli' => function ($query) use ($tipoEntidad) {
        $query->where('TipCodi', $tipoEntidad);
      }, 'almacen'])
      ->formato($formato)
      ->local($local)
      ->mes($mes)
      ->tipo($tipo);

    if ($tipoDoc) {
      $busqueda->tipoDocumento($tipoDoc);
    }

    if ($request->status != null) {
      $busqueda->where('fe_rpta', '=', $request->status);
    }

    if ($tipo == GuiaSalida::INGRESO) {
      $busqueda->orderBy('GuiOper', 'desc');
    } else {
      if ($formato == GuiaSalida::CON_FORMATO) {
        $motivo_traslado = $request->motivo_traslado;
        if ($motivo_traslado) {
          $busqueda->where('motcodi', '=', $motivo_traslado);
          if ($motivo_traslado == MotivoTraslado::TRASLADO_MISMA_EMPRESA) {
            $estado_traslado = $request->estado_traslado;
            if ($estado_traslado) {
              $busqueda->where('e_traslado', '=', $estado_traslado);
            }
          }
        }
        $busqueda->orderBy('GuiNumee', 'desc');
      } else {
        $busqueda->orderBy('GuiOper', 'desc');
      }
    }

    if (!is_null($term)) {

      switch ($filtro) {
        case 'codigo':
          $busqueda->id($term);
          break;
        case 'correlativo':
          $busqueda = $busqueda->where('GuiNumee', 'LIKE', '%' . $term . '%');
          break;
        case 'ref':
          $busqueda->docReferencia($term);
          break;
        case 'cliente':
          $busqueda->whereHas('cli', function ($query) use ($term) {
            $query->where('PCNomb', 'LIKE', '%' . $term . '%')
              ->orWhere('PCRucc', 'LIKE',  $term . '%');
          });
          break;
      }
      $busqueda->get()->toArray();
    }


    return DataTables::of($busqueda)
      ->addColumn('nrodocumento', 'guia_remision.partials.nrodocumento')
      ->addColumn('estado', 'guia_remision.partials.column_estado')
      ->addColumn('accion', 'guia_remision.partials.column_accion')
      ->rawColumns(['accion', 'estado', 'nrodocumento'])
      ->make(true);
  }

  public function search_pendientes(Request $request)
  {
    $empcodi = empcodi();

    $busqueda = GuiaSalida::query()
      ->with([
        'almacen' => function ($q) use ($empcodi) {
          $q->where('EmpCodi', $empcodi);
        },
        'cli' => function ($q) {
          $q->where('TipCodi', 'C')->get();
        }
      ])
      ->where('mescodi', '=', $request->mes)
      ->where('GuiEFor', '=', "1")
      ->where('fe_rpta', '=', "9")
      ->orderBy('GuiOper', 'desc');

    return DataTables::of($busqueda)
      ->addColumn('nrodocumento', 'guia_remision.partials.nrodocumento')
      ->addColumn('estado', 'guia_remision.partials.column_estado')
      ->addColumn('accion', 'guia_remision.partials.column_accion')
      ->rawColumns(['accion', 'estado', 'nrodocumento'])
      ->make(true);
  }


  public function acciones($accion, $id_guia = null, $tipo, $tipoDocumento = null)
  {
    $empcodi = empcodi();
    $tipo = $tipo ?? Guia::SALIDA;
    $tipo_pagos = TipoPago::all();
    $vehiculos = Vehiculo::all();
    $condicion = CondicionVenta::getDefault();
    $tipos_movimientos = TipoMovimiento::getByTipo($tipo);
    $empresa = get_empresa();
    $isIngreso = Guia::SALIDA != $tipo;
    if ($isIngreso) {
      $titulo = "Guia Ingreso";
      $route_store = route('guia_ingreso.store');
      $route_index = route('guia_ingreso.index');
    } else {
      if ($tipoDocumento == null) {
        $titulo = 'Guia De Remision';
        $route_store = GuiaSalida::getRouteStore(GuiaSalida::TIPO_GUIA_REMISION);
        $route_index = route('guia.index');
      } else {
        $titulo = GuiaSalida::getNombreRead($tipoDocumento);
        $route_store = GuiaSalida::getRouteStore($tipoDocumento);
        $route_index = GuiaSalida::getRouteIndex($tipoDocumento);
      }
    }

    $data = [
      'titulo' => ucfirst(strtolower($titulo)),
      'guia' => new GuiaSalida,
      'importar' => false,
      'route_store' => $route_store,
      'route_index' => $route_index,
      'routeIndex' => $route_index,
      'estado_edit' => 'open',
      'isIngreso' => $isIngreso,
      'tipo_pagos' => $tipo_pagos,
      'accion' => $accion,
      'show' => false,
      'vehiculos' => $vehiculos,
      'grupos' => cacheHelper('grupo.all'),
      "condicion" => $condicion,
      EmpresaOpcion::MODULO_MANEJO_STOCK => $empresa->getDataAditional(EmpresaOpcion::MODULO_MANEJO_STOCK),
      'cursor_pointer_producto' => get_option(EmpresaOpcion::CAMPO_CURSOR_PRODUCTO),
      "verificar_deudas" => get_option('ImpSald'),
      "verificar_caja" => get_option('OpcConta'),
      "verificar_almacen" => get_option('DesAuto'),
      "ultimo_codigo" => ClienteProveedor::ultimoCodigo(),
      'ruc' => "",
      'almacenes' => $empresa->almacenes,
      'locales' =>  auth()->user()->locales->load('local'),
      'tipos_movimientos' => $tipos_movimientos,
      'series' => []
    ];

    if ($accion == "create" || $accion == "edit") {

      if ($accion == "edit") {
        $data['guia'] = GuiaSalida::find($id_guia);
        $data['series'] = SerieDocumento::ultimaSerie();
      }

      $tipos_clientes = Cache::rememberForever('tipos_clientes' . $empcodi, function () {
        return TipoCliente::all();
      });
      $tipodocumento = Cache::rememberForever('TipoDocumento' . $empcodi, function () {
        return TipoDocumento::all();
      });
      $departamentos = Cache::rememberForever('departamentos' . $empcodi, function () {
        return Departamento::all();
      });
      $lista_precios = Cache::rememberForever('lista_precios' . $empcodi, function () {
        return ListaPrecio::all();
      });
      $motivo_traslado = Cache::rememberForever('motivo_traslado' . $empcodi, function () {
        return MotivoTraslado::all();
      });
      $transportistas = Transportista::all();
      $empresas_transporte = EmpresaTransporte::all();
      $data['tipos_clientes'] = $tipos_clientes;
      $data['tipos_documentos_clientes'] = $tipodocumento;
      $data['departamentos'] = $departamentos;
      $data['lista_precios'] = $lista_precios;
      $data["empresa"] = $empresa;
      $data["motivo_traslado"] = $motivo_traslado;
      $data["transportistas"] = $transportistas;
      $data["empresas_transporte"] = $empresas_transporte;
      $data["tipos_documentos"] = SerieDocumento::ultimaSerie(true)[0]['series'];
      $vendedores = Cache::rememberForever('vendedores' . $empcodi, function () {
        return Vendedor::all();
      });

      $data["create"] = true;
      $data["monedas"] = Moneda::all();
      $data["tipo_cambio"] = TipoCambioMoneda::ultimo_cambio(false);
      $data["vendedores"] = $vendedores;
      $data["forma_pagos"] = FormaPago::all();
      $data['id_nuevo'] = Venta::UltimoId();
      $data["create"] = 1;
      $data['info'] = $accion == "create" ? false : true;
      $data["tipos_notacredito"] = TipoNotaCredito::all();
    } else {
      $data['guia'] = GuiaSalida::find($id_guia);
      $data['create'] = 0;
      $data['info'] = true;
      $data['show'] = true;
    }
    // 
    return $data;
  }

  public function storeIngreso(GuiaIngresoFromCompraRequest $request, $id)
  {
    try {
      \DB::connection('tenant')->beginTransaction();
      $compra = Compra::find($id);
      $guia = GuiaSalida::storeFromCompra($compra, $request);
      $agregateInventary = $compra->isNotaCredito() ? false : true;
      foreach ($compra->items as $item) {
        GuiaSalidaItem::createItem($item, $guia->GuiOper, 'C', $agregateInventary);
      }
      \DB::connection('tenant')->commit();
      $guia->calculateTotal();
      $compra->updateCantProdEnviados($guia->GuiOper);
      notificacion('Acción exitosa', 'Guia generada exitosamente', 'success');
    } catch (\Exception $e) {
      \DB::connection('tenant')->rollback();
      return response()->json('Error al guardar la guia ' . $e->getMessage(), 500);
    }
    return response()->json([
      'message' => "Guardado Guia de Salida"
    ], 200);
  }

  public function generarDoc(GuiaGenerarDocRequest $request, $id_guia)
  {
    $guia = GuiaSalida::find($id_guia);
    $guia->generateDoc($request);
    if ($guia->isIngreso()) {
      $docSuccessDescripcion = "Compra Generada Satisfactoriamente";
      $route = 'guia_ingreso.index';
    } else {
      $docSuccessDescripcion = "Venta Generada Satisfactoriamente";
      $route = 'guia.index';
    }

    noti()->success($docSuccessDescripcion);
    return redirect()->route($route);
  }
}
