<?php

namespace App\Http\Controllers;

use App\Grupo;
use App\Marca;
use App\Unidad;
use App\Producto;
use App\UnidadProducto;
use App\TipoCambioMoneda;
use App\TipoCambioPrincipal;
use Illuminate\Http\Request;
use App\Jobs\Unidad\UpdateMassive;
use App\Jobs\Unidad\UpdatePrecios;
use Illuminate\Support\Facades\DB;
use App\Jobs\Unidad\UpdateMassiveManual;
use App\Http\Requests\UnidadStoreRequest;
use App\Http\Requests\UnidadDeleteRequest;
use App\Http\Requests\UnidadUpdateRequest;
use App\Http\Requests\updateMasiveRequest;
use App\Http\Requests\UpdateMassiveManualRequest;
use App\Http\Requests\UpdateTipoCambioPublicoRequest;


class UnidadController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_MENUDEO', 'R_PRODUCTO'))->only(['mantenimiento','store','update','edit','delete']);
    $this->middleware(p_midd('A_SHOWPRECIOS', 'R_PRODUCTO'))->only('index');
  }

  public function search( Request $request )
  {
    $this->authorize(p_name('A_SHOWPRECIOS','R_PRODUCTO'));
    $term = $request->input('search.value');
    $local_id = $request->input('local_id');
    $empcodi = empcodi();
    $search = false;

    $busqueda = DB::connection('tenant')->table('unidad')
    ->join('productos' , function($join){
      $join
      ->on('productos.ID', '=', 'unidad.Id')
      ->on('productos.empcodi', '=', 'unidad.empcodi');
    })
    ->join('lista_precio', function ($join) {
      $join
        ->on('lista_precio.LisCodi', '=', 'unidad.LisCodi');
    })    
    ->where('unidad.empcodi' , '=' , $empcodi)
    ->where('productos.empcodi', '=', $empcodi)
    ->where('productos.UDelete', '=', "0")
    ->where('lista_precio.LocCodi' , '=' , $local_id );

    # Filtrar por lista
    if ($request->LisCodi) {
      $busqueda->where('unidad.LisCodi', '=', $request->LisCodi);
    }

    # Filtrar por grupo
    if( $request->grupo_id ){
      $busqueda->where('productos.grucodi', '=', $request->grupo_id);

      # Filtrar por familia
      if ( $request->familia_id ) {
        $busqueda->where('productos.famcodi', '=', $request->familia_id );
      }
    }

    # Filtrar por marca
    if ( $request->marca_id ) {
      $busqueda->where('productos.marcodi' , '=' , $request->marca_id );
    }

    # Buscar por termino
    if( !is_null($term) || trim($term) != "" ){
      is_numeric($term) ?
        $busqueda->where('productos.ProCodi', 'LIKE' , $term . '%' ) :
        $busqueda->where('productos.ProNomb', 'LIKE', '%' . $term . '%');
      $search = true;
    }

    $busqueda->select(
      'unidad.Unicodi',
      'unidad.Id',
      'unidad.UniAbre',
      'unidad.UniPUCD',
      'unidad.UniPUCS',
      'unidad.UniMarg',
      'unidad.UNIPUVS',
      'unidad.UNIPUVD',
      'unidad.UNIPMVS',
      'unidad.UNIPMVD',      
      'productos.ID as id_producto',
      'productos.ProCodi',
      'productos.ProUltC',
      'productos.moncodi',
      'productos.ProNomb'
    );

    if( $search ){
      $busqueda->get()->sortBy('ProCodi');
    }
    else {
      $busqueda->orderBy('productos.ProCodi', 'asc');
    }
    
    return \DataTables::of($busqueda)  
    ->addColumn( 'producto_link', 'unidad.partials.columns.producto' )
    ->addColumn( 'moneda', 'unidad.partials.columns.moneda' )
    ->addColumn( 'acciones', 'unidad.partials.columns.acciones' )
    ->rawColumns([ 'acciones', 'producto_link' ])
    ->smart(false)
    ->make(true);    
  }

  //----
  // Index
  public function mantenimiento($id_producto)
  {
    $empresa = get_empresa();
    $listas = $empresa->listas;
    $producto = Producto::find($id_producto);
    $unidades = UnidadProducto::all();
    $unidad = new Unidad();
    $create = true;
    $tipocambio =  $empresa->getTipoCambioPublico();
    return view('unidad.mantenimiento', compact('producto', 'unidad', 'create', 'unidades', 'listas', 'tipocambio'));
  }

  public function store(UnidadStoreRequest $request, $id_producto)
  {
    $producto = Producto::find($id_producto);
    $data = $request->except('_token');
    // $data["Unicodi"] = Unidad::getUniCodi($id_producto);
    $data["Id"] = $id_producto;
    $data["empcodi"] = $producto->empcodi;
    $data["UNIPUVD"] = $request->input('UniPUVD');
    
    notificacion("Guardado exitoso", "Se ha guardado la unidad de este producto correctamente");
    Unidad::create($data);
    return redirect()->back();
  }

  public function edit($id_unidad)
  {
    $empresa = get_empresa();
    $unidades = cacheHelper('unidadproducto.all');
    $unidad = Unidad::find($id_unidad);
    $producto = $unidad->producto();
    $create = false;
    $listas = $empresa->listas;
    $tipocambio =  $empresa->getTipoCambioPublico();

    return view(
      'unidad.mantenimiento',
      compact('producto', 'unidad', 'create', 'unidades', 'listas', 'tipocambio')
    );
  }

  public function delete(UnidadDeleteRequest $request, $id_unidad)
  {
    $unidad = Unidad::find($id_unidad);
    Unidad::destroy($id_unidad);
    $unidad->delete();
    notificacion("Se ha borrado la unidad exitosamente", "");
    return redirect()->back();
  }


  public function update(Request $request, $id_unidad)
  {
    $data = $request->except('_token', 'LisCodi');
    $unidad = Unidad::find($id_unidad);
    $data['UNIPUVD'] = $data['UniPUVD'];
    $unidad
    ->fill($data)
    ->save();

    $unidad->updateProductoPrices();

    notificacion("Guardado exitoso", "Se ha guardado modificado la información de la unidad");
    return redirect()->route('productos.unidad.mantenimiento', $unidad->Id);
  }
  //----



  public function index( $producto_id = '')
  {
    $tc_venta = get_empresa()->opcion->tipo_cambio_publico;
    $tc_sunat_original = TipoCambioPrincipal::lastTC();
    $tc_sunat = is_null($tc_sunat_original) ? 3.591 : $tc_sunat_original->venta;
    $tc_sunat_fecha = is_null($tc_sunat_original) ? 3.591 : $tc_sunat_original->fecha;


    $decimales_soles = get_option('DecSole');
    $decimales_dolares = get_option('DecDola');

    $data = [
      'tc_venta' => $tc_venta,
      'tc_sunat' => $tc_sunat,
      'tc_sunat_fecha' => $tc_sunat_fecha,
      'producto_id' => $producto_id,
      'decimales_soles' => $decimales_soles,
      'decimales_dolares' => $decimales_dolares,

    ];
    
    return view('unidad.index' , $data );
  }

 

  public function updatePrices( UnidadUpdateRequest $request , $id_unidad )
  {
    $this->authorize(p_name('A_UPDATEPRECIOS', 'R_PRODUCTO'));

    $data = $request->only('UniPUCD','UniPUCS','UniMarg','UNIPUVS','UNIPUVD', 'UNIPMVS', 'UNIPMVD');

    $data = $request->only('UniPUCD', 'UniPUCS', 'UniMarg', 'UNIPUVS', 'UNIPUVD', 'UNIPMVS', 'UNIPMVD');

    $decimales = getEmpresaDecimals();

    $data['UNIPUVS'] = math()->addDecimal($data['UNIPUVS'], $decimales->soles);
    $data['UNIPUVD'] = math()->addDecimal($data['UNIPUVD'], $decimales->dolares);
    $data['UNIPMVS'] = math()->addDecimal($data['UNIPMVS'], $decimales->soles);
    $data['UNIPMVD'] = math()->addDecimal($data['UNIPMVD'], $decimales->dolares);


    $unidad = Unidad::find($id_unidad);
    $unidad->fill($data)->save();      
    $unidad->updateProductoPrices();

    return response()->json([
      'data' => $unidad->toArray()
      ]
    );
  }

  public function updatePreciosByTipoCambio( UpdateTipoCambioPublicoRequest $request )
  {
    $this->authorize(p_name('A_UPDATEPRECIOSTIPOCAMBIO', 'R_PRODUCTO'));

    set_time_limit(200);

    (new UpdatePrecios($request->tipo_cambio))->handle();

    return response()->json([ 
      'message' => 'Actualizaciòn exitosa',
      'tipo_cambio' => $request->tipo_cambio,
    ], 200 );
  }


  public function updateMasive(updateMasiveRequest $request)
  {
    $this->authorize(p_name('A_UPDATEPRECIOSMASIVE', 'R_PRODUCTO'));

    set_time_limit(200);

    (new UpdateMassive( 
      $request->tipo,
      $request->campo,
      $request->value,
      $request->lista_id,
      $request->grupo_id,
      $request->familia_id,
      $request->marca_id,
      $request->local_id
    ))->handle();

    return response()->json([
      'message' => 'Actualizaciòn exitosa',
    ], 200);
  }

  public function updateMasiveManual( UpdateMassiveManualRequest $request)
  {
    $this->authorize(p_name('A_UPDATEPRECIOSMASIVE', 'R_PRODUCTO'));
    
    set_time_limit(200);

    (new UpdateMassiveManual($request->data))->handle();

    return response()->json([
      'message' => 'Actualizaciòn exitosa',
    ], 200);
  }

  public function getFiltros(Request $request)
  {
    $local = auth()->user()->localCurrent()->loccodi;
    $locales = auth()->user()->locales->load('local.listas');
    $listas = $locales->where('loccodi', $local ) ->first()->local->listas;

    return view('unidad.partials.filters', [
      'locales' => $locales,
      'listas' => $listas,
      'local_current' => $local,
      'grupos' => Grupo::all(),
      'marcas' => Marca::all()
    ]);
  }

}