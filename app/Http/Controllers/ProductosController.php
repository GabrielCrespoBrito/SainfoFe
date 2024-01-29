<?php

namespace App\Http\Controllers;

use App\Grupo;
use App\Local;
use App\Marca;
use App\Venta;
use Validator;
use App\Moneda;
use App\Unidad;
use App\Cliente;
use App\Producto;
use App\Procedencia;
use App\OpcionEmpresa;
use App\TipoExistancia;
use App\UnidadProducto;
use App\TipoCambioMoneda;
use Illuminate\Http\Request;
use App\Jobs\Producto\UpdateInventarios;
use App\Http\Requests\ProductoStoreRequest;
use App\Jobs\Producto\ReprocesarCantidades;
use App\Http\Controllers\Traits\ImportExcel;
use App\Http\Requests\ProductoUpdateRequest;
use App\Http\Requests\Producto\ConsultDataProductoRequest;
use App\Models\TomaInventario\TomaInventario;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class ProductosController extends Controller
{
  public $producto;
  public $tipo_cambio_moneda;
  public $opcion_empresa;
  public $empcodi;

  public function __construct()
  {
    $this->middleware([p_midd('A_INDEX', 'R_PRODUCTO'), 'familia.producto.creacion'])->only('index');
    $this->producto = new Producto;
    $this->tipo_cambio_moneda = new TipoCambioMoneda;
    $this->opcion_empresa = new OpcionEmpresa;
  }

  public function index()
  {
    $empcodi = empcodi();
    $grupos       = Grupo::where('empcodi', $empcodi)->get();
    $unidades     = Unidad::where('empcodi', $empcodi)->get();
    $marcas       = Marca::where('empcodi', $empcodi)->get();
    $procedencias = Procedencia::all();
    $tipos_existencias = TipoExistancia::all();
    $monedas      = Moneda::all();
    $unidades     = UnidadProducto::all();
    $locales = $locales ?? get_empresa()->almacenes;
    $search_parameter = get_option('probusc');

    return view('productos.index', [
      'grupos' => $grupos,
      'unidades' => $unidades,
      'marcas' => $marcas,
      'procedencias' => $procedencias,
      'search_parameter' => $search_parameter,
      'tipos_existencias' => $tipos_existencias,
      'monedas' => $monedas,
      'locales' => $locales,
    ]);
  }

  public function search(Request $request)
  {
    $listas = auth()->user()->listasCode();
    $term = str_replace('*', '%', $request->search['value']);
    $campo = $request->input('campo_busqueda', 'nombre');
    $grupo = $request->input('grupo');
    $familia = $request->input('familia');
    $marca = $request->input('marca');

    $busqueda = Producto::query()
      ->with([
        'marca_',
        'marca',
        'unidades_' => function ($q) use ($listas) {
          $q->whereIn('LisCodi', $listas);
        },
        'unidades_.lista'
      ])->whereHas('unidades_', function($q) use($listas) {
          $q->whereIn('LisCodi', $listas);
      });


    if ($campo == "codigo") {
      $busqueda
      ->where('ProCodi', 'LIKE', $term . '%')
      ->orderBy('ProCodi', 'asc');
    } elseif ($campo == "codigo_barra") {
      $busqueda->where('ProCodi1', 'LIKE', $term . '%');
    } elseif ($campo == "nombre") {
      $busqueda
      ->where('ProNomb', 'LIKE', '%' . $term . '%')
      ->orderBy('ProCodi', 'asc');
    }

    if ($grupo) {
      $busqueda->where('grucodi', $grupo);
      if ($familia) {
        $busqueda->where('famcodi', $familia);
      }
    }

    if ($marca) {
      $busqueda->where('marcodi', $marca);
    }

    return datatables()->of($busqueda)
      ->addColumn('accion', 'productos.partials.column_accion')
      ->addColumn('unidades', function ($model) {

        $unidadesArr = [];
        foreach ($model->unidades_ as $unidad) {
          $data = $unidad->toArray();
          $data['UniAbre'] = $unidad->UniAbre . ' - ' . optional($unidad)->lista->LisNomb;
          $data['UniA'] = $unidad->UniAbre;
          array_push($unidadesArr, $data);
        }
        return $unidadesArr;
      })
      ->RawColumns(['accion', 'ProNomb'])
      ->toJson();
  }

  public function search_select2(Request $request)
  {
    $term = $request->data;
    if (empty($term)) {
      return \Response::json([]);
    }

    $productos = Producto::where('ProCodi', 'like', $term . '%')
      ->orWhere('ProNomb', 'like', '%' . $term . '%')
      ->get()
      ->take(6);

    $data = [];

    $fieldId = $request->has('id') ? 'ID' : 'ProCodi';

    if ($productos->count()) {
      foreach ($productos as $producto) {
        $text = "[" . $producto->ProCodi . "] " . $producto->ProNomb;
        $data[] = [
          'id' => $producto->{$fieldId},
          'text' => $text,
          'data' => ''
        ];
      }
    }

    return \Response::json($data);
  }

  public function search_productos_vendidos(Request $request)
  {
    $cliente = Cliente::where('PCRucc', $request->id_cliente)
      ->where('EmpCodi', empcodi())
      ->first();

    $ventas = Venta::where('PCCodi', $cliente->PCCodi)->get();
    $productos = [];

    foreach ($ventas as $venta) {
      foreach ($venta->items as $item) {
        $item_array = $item->toArray();
        $item_array['nro_documento'] = $venta->VtaNume;
        $item_array['fecha'] = $venta->VtaFvta;
        array_push($productos, $item_array);
      }
    }
    return $productos;
  }

  public function consultar_datos(Request $request)
  {
    $producto = Producto::with('CodSunat')->where('ProCodi', $request->codigo)->first();
    $producto = $producto->toArray();
    $producto['profoto'] = null;
    return $producto;
  }

  public function consultar_alldatos(ConsultDataProductoRequest $request)
  {
    $this->validate($request, [
      'codigo' => 'required'
    ]);

    $localActual = auth()->user()->localCurrent();
    //
    $campo = $request->input('campo', 'ProCodi');
    $producto = Producto::with(['unidades.lista'])->where($campo, $request->codigo)->first();
    // 
    $unidad_arr = [];
    $unidad_principal_arr = [];
    // 
    foreach ($producto->unidades as $unidad) {

      $data = $unidad->toArray();
      $data['UniAbre'] = $unidad->withListaName();

      if ($unidad->lista->LocCodi == $localActual->loccodi) {
        array_push($unidad_arr, $data);
      } else {
        array_push($unidad_principal_arr, $data);
      }
    }

    if (!count($unidad_arr)) {
      $unidad_arr = $unidad_principal_arr;
    }


    $marca = $producto->marca;
    $codigo_barra = $producto->ProCodi1;
    $producto = $producto->toArray();
    $producto['profoto'] = null;
    $producto['ProCodi1'] = $codigo_barra;
    $producto['unidades'] = $unidad_arr;
    // 
    return [
      'producto' => $producto,
      'unidades' => $unidad_arr,
      'marca'    => $marca,
      'campo'    => $campo,
    ];
  }

  public function store(ProductoStoreRequest $request)
  {
    $decimales = getEmpresaDecimals();

    $codigo_barra =  $request->input('codigo_barra', null);
    $data = [];
    $data['ProCodi'] = strtoupper($request->numero_operacion);
    $data['ProCodi1'] = $codigo_barra;
    $data['famcodi'] = $request->familia;
    $data['grucodi'] = $request->grupo;
    $data['marcodi'] = $request->marca;
    $data['ProNomb'] = strtoupper($request->nombre);
    $data['proesta'] = $request->estado;
    $data['prosto1'] = 0;
    $data['prosto2'] = 0;
    $data['prosto3'] = 0;
    $data['prosto4'] = 0;
    $data['prosto5'] = 0;
    $data['prosto6'] = 0;
    $data['prosto7'] = 0;
    $data['prosto8'] = 0;
    $data['prosto9'] = 0;
    $data['prosto10'] = 0;
    $data['unpcodi'] = $request->unidad;
    $data['empcodi'] = get_empresa('id');
    $data['tiecodi'] = $request->tipo_existencia;
    $data['moncodi'] = $request->moneda;
    $cambios_moneda_compra = Moneda::cambios_moneda($request->moneda, $request->costo);
    $data['ProPUCD'] = $cambios_moneda_compra['dolar'];
    $data['ProPUCS'] = $cambios_moneda_compra['sol'];
    $data['ProMarg'] = $request->utilidad;
    $precios_venta = Producto::calcularPrevioVentaStatic($request->precio_venta, $data['moncodi'] );
    $data['ProPUVD'] = decimal($precios_venta["dolar"], $decimales->dolares);
    $data['ProPUVS'] = decimal($precios_venta["sol"], $decimales->dolares);
    $precios_min_venta = Producto::calcularPrecioMin($request->precio_min_venta, $data['moncodi']);
    $data['ProPMVS'] = decimal($precios_min_venta["sol"], $decimales->soles);    
    $data['ProPMVD'] = decimal($precios_min_venta["dolar"], $decimales->dolares);
    $data['provaco'] = 0;
    $data['proigco'] = 0;
    $data['ProMarg1'] = 0;
    $data['ProPUVS1'] = 0;
    $data['ProPUVD1'] = 0;
    $data['proubic'] = 0;
    $data['ProUltC'] = 0;
    $data['proproms'] = 0;
    $data['propromd'] = 0;
    $data['proigvv'] = 0;
    $data['ProPUCL'] = 0;
    $data['ProDcto1'] = 0;
    $data['Prodcto2'] = 0;
    $data['Prodcto3'] = 0;
    $data['ProIgv1'] = 0;
    $data['ProPerc1'] = 0;
    $data['ctavta'] = $request->cuenta_venta;
    $data['ctacpra'] = $request->cuenta_compra;
    $data['Proobse'] = $request->descripcion;
    $data['prouso'] = $request->modo_uso;
    $data['proingre'] = $request->ingredientes;
    $data['ProSTem'] = $request->input('ProSTem');
    $data['ProcCodi'] = 0;
    $data['BASEIGV'] = $request->base_igv;
    $data['proigvv'] = $request->igv_porc;
    $data['ProPeso'] = $request->peso;
    $data['ISC'] = $request->isc;
    $data['icbper'] = $request->icbper;
    $data['incluye_igv'] = $request->input('incluye_igv', 0);
    $data['ProPerc'] = $request->input('ProPerc', 0);
    $data['proubic'] = $request->ubicacion;
    $data['Promini'] = $request->stock_minimo;
    $data['profoto'] = null;
    $data['profoto2'] = $request->input('profoto2');
    $data['User_Crea'] = auth()->user()->usulogi;
    $data['User_ECrea'] = gethostname();
    $productoId = Producto::insertGetId($data);
    Unidad::createFromProducto($productoId, $data, null );
    get_empresa()->sumarConsumo('productos');
    return [ 'ProCodi' => $data['ProCodi'] ];
  }

  public function update(ProductoUpdateRequest $request)
  {
    $this->authorize(p_name('A_EDIT', 'R_PRODUCTO'));
    $imagen_nombre = NULL;

    // if ($request->file('imagen')) {
    //   $file = $request->file('imagen');
    //   $extencion = \strtolower($file->getClientOriginalExtension());
    //   $imagen_nombre = time() . str_random(5) . "." . $extencion;
    //   $file->storeAs('/public/' . session('empresa'), $imagen_nombre);
    // }

    $producto = Producto::find($request->codigo);
    $producto->ProCodi = strtoupper($request->numero_operacion);
    $producto->ProCodi1 = strtoupper($request->codigo_barra);
    $producto->famcodi = $request->familia;
    $producto->grucodi = $request->grupo;
    $producto->marcodi = $request->marca;
    $producto->ProNomb = strtoupper($request->nombre);
    $producto->proesta = $request->estado;
    $producto->unpcodi = $request->unidad;
    $producto->tiecodi = $request->tipo_existencia;
    $producto->moncodi = $request->moneda;
    $cambios_moneda_compra = Moneda::cambios_moneda($request->moneda, $request->costo);
    $producto->ProPUCD = $cambios_moneda_compra['dolar'];
    $producto->ProPUCS = $cambios_moneda_compra['sol'];
    $producto->ProMarg = $request->utilidad;
    $precios_venta = $producto->calcularPrecioVenta($request->precio_venta);
    $producto->ProPUVD = $precios_venta["dolar"];
    $producto->ProPUVS = $precios_venta["sol"];
    $precios_min_venta = Producto::calcularPrecioMin($request->precio_min_venta, $request->moneda);
    $producto->ProPMVD = $precios_min_venta["dolar"];
    $producto->ProPMVS = $precios_min_venta["sol"];
    $producto->ctavta = $request->cuenta_venta;
    $producto->ctacpra = $request->cuenta_compra;
    $producto->Proobse = $request->descripcion;
    $producto->prouso = $request->modo_uso;
    $producto->proingre = $request->ingredientes;
    $producto->ProcCodi = 0;
    $producto->ProSTem = $request->input('ProSTem');
    $producto->BASEIGV = $request->base_igv;
    $producto->proigvv = $request->igv_porc;
    $producto->ProPeso = $request->peso;
    $producto->ISC = $request->isc;
    $producto->icbper = $request->input('icbper', 0);
    $producto->incluye_igv = $request->input('incluye_igv', 0);
    $producto->ProPerc = $request->input('ProPerc', 0);
    $producto->proubic = $request->ubicacion;
    $producto->Promini = $request->stock_minimo;
    $producto->profoto = $imagen_nombre;
    $producto->profoto2 = $request->profoto2;
    $producto->User_Modi = auth()->user()->usulogi;
    $producto->User_EModi = gethostname();
    $producto->save();

    if ($producto->wasChanged('ProPUCD', 'ProPUCS', 'ProMarg', 'ProPUVS', 'ProPUVD', 'unpcodi', 'ProPeso')) {
      $producto->updateUnidadPrincipal();
    }
    $producto = $producto->toArray();
    return $producto;
  }

  public function consultar_noperacion(Request $request)
  {
    return Producto::consultar_noperacion($request->grupo, $request->familia, $request->marca);
  }

  public function consultar_codigo(Request $request)
  {
    return Producto::UltimoID();
  }

  public function eliminar(Request $request)
  {
    $this->authorize(p_name('A_DELETE', 'R_PRODUCTO'));

    $producto = Producto::findByProCodi($request->id);
    $result = $producto->useInDocument();
    
    if ( ! $result->success   ) {
      if( $result->codigo_sitio == "toma_inventario" ){
        return response()->json(['message' => sprintf("No puede cambiar eliminar el producto por que esta siendo utiliza en Toma de Inventario")], 400);

      }
    }


    if (!$result->success) {
      $producto->UDelete = "*";
      $producto->save();
      return response()->json(['message' => 'Producto Ocultado'], 200);
    } 

    else {
      foreach ($producto->unidades as $unidad) {
        Unidad::destroy($unidad->Unicodi);
        $unidad->delete();
      }
      $producto->delete();
      return response()->json(['message' => 'Producto eliminado'], 200);
    }
  }


  public function import_data()
  {
    return view('productos.import_data');
  }

  public function import_ventas()
  {
    return view('productos.import_ventas', ['timeout' => 99999]);
  }


  public function import_store(Request $request)
  {
    $request->validate(['excel' => 'required|mimes:xlsx'], ['excel.required'  => 'Tiene que subir el archivo excel']);
    set_time_limit(600);

    $success = true;
    $errors = [];

    try {
      // \DB::beginTransaction();
      $hojas_procesar = array_keys($request->except('hojas', 'excel'));
      $importer = new ImportExcel($request->file('excel'), $hojas_procesar);
      $success = $importData['success'];
      $errors = $importData['errors'];
      \DB::commit();
    } catch (\QueryException | Throwable | \Exception | ErrorException | FatalThrowableError $e) {
      $errors[] = "Ha habido un inconveniente al guardar los documentos: ({$e->getMessage()}";
      $success = false;

      // \DB::rollback();
      return response()->json($e->getMessage(), 400);
      error_clear_last();
    }

    if ($success) {
      return response()->json(['message' => "Se ha guardado la información satisfactoriamente"], 200);
    } else {
      return response()->json(['errors' => $errors], 400);
    }
  }

  public function excelEjemplo($tipo = "productos")
  {
    ob_start();
    $nameFile = $tipo == "productos" ? 'productos.xlsx' : 'ventas.xlsx';
    $path = public_path(file_build_path('static', 'excels', $nameFile));
    ob_end_clean();
    return response()->download($path);
  }

  public function downloadProduct()
  {
  }


  public function reprocesar()
  {
    return back();

    try {
      \DB::beginTransaction();
      ReprocesarCantidades::dispatchNow();
      \DB::commit();
      notificacion('Listo', 'Reprocesamiento de productos listos', 'success');
      $success = true;
    } catch (\QueryException | Throwable | \Exception | ErrorException | FatalThrowableError $e) {
      $message = strpos($e->getMessage(), "habido") !== false ? "ex" . $e->getMessage() : 'Ha habido un inconveniente al guardar los documentos: (' . $e->getMessage() . ')';
      $code = 400;
      notificacion('Error', 'Reprocesamiento de productos fallo: '  . $message, 'error');
      \DB::rollback();
    }

    return redirect()->back();
  }

  public function updateStock(Request $request)
  {
    $this->authorize(p_name('A_UPDATESTOCK', 'R_PRODUCTO'));

    $stocks = [];
    try {
      \DB::beginTransaction();
      $local = $request->local == 'todos' ? null : Local::findOrfail($request->local);
      $producto =  Producto::findByProCodi($request->id);
      $producto->reProcess($local);
      $stocks = $producto->refresh()->getStocks();
      $message = ' Se ha actualizado el stock del producto';
      $code = 200;
    } catch (\QueryException | Throwable | \Exception | ErrorException | FatalThrowableError $e) {
      $message = strpos($e->getMessage(), "habido") !== false ? "ex" . $e->getMessage() : 'Ha habido un inconveniente al guardar los documentos: (' . $e->getMessage() . ')';
      $code = 400;
    }

    return response()->json(['message' => $message, 'stocks' => $stocks], $code);
  }


  public function updateInventarios(Request $request)
  {
    $local = $request->local_inventario == "todos" ? null : $request->local_inventario;
    $update_inventarios = new UpdateInventarios($local, $request->fecha_inventario);
    $update_inventarios->handle();

    noti()->success('Acción exitosa', 'Actualización de inventario realizada satisfactoriamente');
    return back();
  }

  public function updateAlmacen(Request $request, $producto_id)
  {
    $producto = Producto::findByProCodi($producto_id);
    $producto->reProcess();
    $producto->refresh();

    return response()->json([
      "1" => decimal($producto->prosto1),
      "2" => decimal($producto->prosto2),
      "3" => decimal($producto->prosto3),
      "4" => decimal($producto->prosto4),
      "5" => decimal($producto->prosto5),
      "6" => decimal($producto->prosto6),
      "7" => decimal($producto->prosto7),
      "8" => decimal($producto->prosto8),
      "9" => decimal($producto->prosto9),
      "10" => decimal($producto->prosto10),
      "total" => decimal($producto->getTotalInventario()),
    ]);
  }

  public function updateUltimoCosto ()
  {
    $productosGroup = Producto::all()->chunk(100);  

    foreach( $productosGroup as $productoGroup ){
      foreach( $productoGroup as $producto ){
        $producto->updateProductUltimoCosto();
      }
    }

    noti()->success('Acción exitosa', 'Se ha actualizado el ultimo costo de los productos');
    return back();
  }

}


