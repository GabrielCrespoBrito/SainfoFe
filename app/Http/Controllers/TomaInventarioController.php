<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Events\CreateTomaInventario;
use App\Events\CreateTomaInventarioFromExcell;
use App\Http\Requests\TomaInventarioImportExcellRequest;
use App\Http\Requests\TomaInventarioRequest;
use App\Jobs\Producto\UpdateStocks;
use App\Models\TomaInventario\TomaInventario;
use App\Util\ExcellGenerator\ProductosTomaInventarioExcell;
use App\Util\PDFGenerator\PDFGenerator;

class TomaInventarioController extends Controller
{
  public $model;

  public function __construct()
  {
    ini_set('max_input_vars', 10000);

    $this->model = new TomaInventario();
    $this->middleware(p_midd('A_TOMAINVENTARIO', 'R_PRODUCTO'));
  }

  public function index()
  {
    $locales = auth()->user()->locales->load('local');
    return view('toma_inventario.index', compact('locales'));
  }

  public function search(Request $request)
  {
    $busqueda = TomaInventario::where('LocCodi', $request->loccodi)->orderByDesc('InvCodi');

    return DataTables::of($busqueda)
      ->addColumn('link', function ($model) {
        return sprintf('<a href="%s">%s</a>', route('toma_inventario.show', $model->id), $model->InvNomb);
      })
      ->addColumn('estado', function ($model) {
        return $model->presenter->getEstadoColumn();
      })
      ->addColumn('accion', 'partials.column_accion_model')
      ->rawColumns(['link', 'estado', 'accion'])
      ->make(true);
  }

  public function create(Request $request)
  {
    if (!$loccodi = $request->input('loccodi')) {
      noti()->warning('Necesita Selecciona un Local');
      return back();
    }

    if (get_empresa()->hasTomaInventarioPendiente($loccodi)) {
      noti()->warning('Toma Inventario Pendiente', 'No puede registrar una nueva toma de inventario, mientras tenga una pendiente');
      return back();
    }

    $locales = auth()->user()->locales->load('local');
    $loccodi = $loccodi;
    $model = $this->model;
    $model->InvNomb = $model->getNextName();
    $model->InvFech = date('Y-m-d');
    $route = route('toma_inventario.store');
    return view('toma_inventario.create', compact('locales', 'loccodi', 'model', 'route'));
  }

  public function store(TomaInventarioRequest $request)
  {
    try {
      DB::connection('tenant')->beginTransaction();
      $toma_inventario = (new TomaInventario)->repository()->create($request->only('LocCodi', 'InvFech', 'InvNomb', 'InvObse', 'InvEsta'));
      event(new CreateTomaInventario($toma_inventario, $request->items_data));
      DB::connection('tenant')->commit();
    } catch (\Throwable $th) {
      DB::connection('tenant')->rollback();
      return response()->json(['message' => $th->getMessage()], 400);
    }

    return response()->json(['message' => 'Acción exitosa'], 200);
  }

  public function show($id)
  {
    $locales = auth()->user()->locales->load('local');
    $model = TomaInventario::find($id);
    $accion = "show";
    $route = route('toma_inventario.update');
    return view('toma_inventario.show', compact('accion', 'locales', 'model', 'route'));
  }

  public function edit($id)
  {
    $model = TomaInventario::find($id);
    if ($model->isCerrada()) {
      return redirect()->route('toma_inventario.show', $id);
    }
    $accion = "edit";
    $route = route('toma_inventario.update', $id);
    return view('toma_inventario.edit', compact('accion', 'model', 'route'));
  }

  public function update(TomaInventarioRequest $request, $id)
  {
    try {
      DB::connection('tenant')->beginTransaction();
      $toma_inventario = (new TomaInventario)->repository()->update($request->only('InvFech', 'InvNomb', 'InvObse', 'InvEsta'), $id);
      event(new CreateTomaInventario($toma_inventario, $request->items_data, false));
      DB::connection('tenant')->commit();
    } catch (\Throwable $th) {
      DB::connection('tenant')->rollback();
      logger($th);
      return response()->json(['message' => $th->getMessage()], 400);
    }

    return response()->json(['message' => 'Acción exitosa'], 200);
  }

  public function destroy($id)
  {
    $toma = TomaInventario::find($id);
    //
    if ($toma->isCerrada()) {
      noti()->error('No puede eliminar una Toma de Inventario Cerrada');
      return back();
    }

    $toma->delete();
    noti()->success('Acción exitosa', 'Se ha eliminado la Toma de Inventario');
    return redirect()->route('toma_inventario.index');
  }

  public function updateStocks(Request $request)
  {
    $data = (new UpdateStocks($request))->handle();
    return response()->json(['data' => $data]);
  }

  public function exportExcellProducto()
  {
    $excellGenerator = new ProductosTomaInventarioExcell([], 'sainfo-excell-productos');

    $info = $excellGenerator
      ->generate()
      ->store();

    return [
      'content' => base64_encode(file_get_contents($info['full'])),
      'name' => $info['file']
    ];
  }

  public function importExcellProducto(TomaInventarioImportExcellRequest $request)
  {
    try {
      DB::connection('tenant')->beginTransaction();
      $data = TomaInventario::createDataForExcell($request);
      $tomaInventario = (new TomaInventario)->repository()->create($data);
      event(new CreateTomaInventarioFromExcell($tomaInventario, $request));
      DB::connection('tenant')->commit();
    } catch (\Throwable $th) {
      DB::connection('tenant')->rollback();
      return response()->json(['message' => $th->getMessage()], 400);
    }

    return response()->json(['message' => 'Toma de Inventario Creada Exitosamente'], 200);
  }




  public function pdf($id)
  {
    $toma_inventario = TomaInventario::find($id);
    $empresa = get_empresa();

    $data = [
      'toma_inventario' => $toma_inventario,
      'nombre_reporte' => 'Toma de Inventario',
      'local' => $toma_inventario->local->LocNomb,
      'fecha' => $toma_inventario->InvFech,
      'nombre_toma' => $toma_inventario->InvNomb,
      'observacion' => $toma_inventario->InvObse,
      'estado' => $toma_inventario->getNombreEstado(),
      'ruc' => $empresa->ruc(),
      'nombre_empresa' => $empresa->nombre(),
    ];

    $pdf = new PDFGenerator(view('toma_inventario.pdf', $data),  PDFGenerator::HTMLGENERATOR);
    $pdf->generator->setGlobalOptions(PDFGenerator::getSetting('a4', PDFGenerator::HTMLGENERATOR));

    $namePDF = $toma_inventario->getNamePDF();
    $tempPath = file_build_path('temp', $namePDF);
    $pdf->save($tempPath);

    return response()->download($tempPath, $namePDF);
  }
}
