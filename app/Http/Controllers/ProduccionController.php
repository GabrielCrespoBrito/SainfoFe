<?php

namespace App\Http\Controllers;

use App\PDFPlantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produccion\Produccion;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Requests\ProduccionRequest;
use App\Repositories\ProduccionRepository;
use Codexshaper\WooCommerce\Facades\Product;

class ProduccionController extends Controller
{
  public $repository;

  public function __construct()
  {
    $this->repository = new ProduccionRepository();
  }

  public function index()
  {
    $producciones = Produccion::orderByDesc('USER_FCREA')->get();
    $estados = Produccion::getEstados();
    return view('produccion.index', compact('producciones', 'estados'));
  }

  public function cambiarEstado(Request $request, $id)
  {
    $produccion = Produccion::find($id);
    DB::connection('tenant')->beginTransaction();
    try {
      $produccion->updateEstado($request->estado);
      if ($produccion->isEstadoCulminado()) {
        $produccion->createGuias();
        $produccion->setCostos();
      }
    } catch (\Throwable $th) {
      DB::connection('tenant')->rollBack();
      noti()->error('Error', $th->getMessage());
      return redirect()->route('produccion.index');
    }

    DB::connection('tenant')->commit();
    noti()->success('Accion Exitosa', 'Se ha cambiado el estado correctamente');
    return redirect()->route('produccion.index');
  }

  public function create()
  {
    $produccion = new Produccion();
    return view('produccion.create', compact('produccion'));
  }

  public function store(ProduccionRequest $request)
  {
    $produccion = $this->repository->create($request->all());
    return response()->json(['data' => $produccion]);
  }

  public function show($id)
  {
    $produccion = Produccion::find($id);
    return view('produccion.show', compact('produccion'));
  }

  public function edit($id)
  {
    $produccion = Produccion::find($id);

    abort_if($produccion->isEstadoCulminado() || $produccion->isEstadoAnulado(), 500, "No se Puede Modificar una Producción Anulada o Culminada");

    return view('produccion.edit', compact('produccion'));
  }

  public function update(ProduccionRequest $request, $id)
  {
    $produccion = Produccion::find($id);

    abort_if($produccion->isEstadoCulminado() || $produccion->isEstadoAnulado(), 500, "No se Puede Modificar una Producción Anulada o Culminada");

    $produccion = $this->repository->update($request->all(), $id);
    return response()->json(['data' => $produccion]);
  }

  public function destroy($id)
  {
    $produccion = Produccion::find($id);

    if ($produccion->isEstadoCulminado()) {
      noti()->error('No se Puede eliminar una Producción Culminada');
      return redirect()->route('produccion.index');
    }

    $this->repository->delete($id);
    noti()->success('Accion Exitosa', 'Se ha eliminado correctamente el registro');
    return redirect()->route('produccion.index');
  }

  public function pdf()
  {
    $producciones = Produccion::with('items')->get();
    $empresa = get_empresa();
    $pdf = new PDFGenerator(view('produccion.pdf', compact('producciones', 'empresa')), PDFGenerator::HTMLGENERATOR);
    $pdf->generator->setGlobalOptions(PDFGenerator::getSetting(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR));
    return $pdf->generator->generate();
  }

}
