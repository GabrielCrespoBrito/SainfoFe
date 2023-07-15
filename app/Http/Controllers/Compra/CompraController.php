<?php

namespace App\Http\Controllers\Compra;

use App\Compra;
use App\PDFPlantilla;
use Illuminate\Http\Request;
use App\Events\CompraCreated;
use App\Events\CompraUpdated;
use App\Events\CompraDeleting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFGenerator;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\CompraStoreRequest;

class CompraController extends Controller
{
  use
    CompraReporte,
    CompraTrait;

  public function __construct()
  {
    $this->middleware(p_midd('A_INDEX', 'R_COMPRA'))->only('index');
    $this->middleware(p_midd('A_DELETE', 'R_COMPRA'))->only('destroy');
    $this->middleware(p_midd('A_EDIT', 'R_COMPRA'))->only('edit');
    $this->middleware(p_midd('A_SHOW', 'R_COMPRA'))->only('show');
    $this->middleware(p_midd('A_COMPRA', 'R_REPORTE'))->only(['showReporte', 'pdfReporte']);
    $this->compra = new Compra;
  }

  public function index()
  {
    $locales = auth()->user()->locales;
    // 
    return view('compras.index', [
      'locales' => $locales
    ]);
  }

  public function search(Request $request)
  {
    $busqueda = $this->getSearch($request);
    return DataTables::of($busqueda)
      ->addColumn('nro_venta', function ($compra) {
        return "<a href=" . route('compras.show', $compra->CpaOper) . ">" . $compra->CpaOper  .  "</a>";
      })
      ->addColumn('accion', 'partials.column_accion_model')
      ->addColumn('alm', 'partials.column_alm')
      ->rawColumns(['nro_venta', 'accion', 'alm'])
      ->make(true);
  }

  public function create()
  {
    $this->authorize(p_name('A_CREATE', 'R_COMPRA'));

    $data = $this->getDataForm("create");
    return view('compras.create', $data);
  }


  public function store(CompraStoreRequest $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_COMPRA'));
    $code = 200;
    $message = "Se ha registrado al compra correctamente";
    try {
      DB::connection('tenant')->beginTransaction();
      $data = $request->except(['items']);
      $compra = Compra::createCustom($data, $request->totales);
      event(new CompraCreated($compra, $request->items));
      DB::connection('tenant')->commit();
    } catch (\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
      $message = $e->getMessage();
      $code = 400;
      DB::connection('tenant')->rollBack();
    } catch (\ErrorException $e) {
      $message = $e->getMessage();
      $code = 400;
      DB::connection('tenant')->rollBack();
    }

    return response()->json(['message' => $message], $code);
  }

  public function show($id)
  {
    $data = $this->getDataForm("show", $id);
    return view('compras.show', $data);
  }

  public function edit($id)
  {
    $data = $this->getDataForm("edit", $id);
    return view('compras.edit', $data);
  }

  public function update(CompraStoreRequest $request, $id)
  {
    $this->authorize(p_name('A_EDIT', 'R_COMPRA'));

    $compra = Compra::findOrfail($id);
    $code = 200;
    $message = "Se ha modificado la compra correctamente";
    
    try {
      DB::connection('tenant')->beginTransaction();
      $data = $request->except(['items']);
      $compra->updateCustom($data, $request->totales);
      event(new CompraUpdated($compra, $request->items));
      DB::connection('tenant')->commit();
    } catch (\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
      $message = $e->getMessage();
      $code = 400;
      DB::connection('tenant')->rollBack();
    } catch (\ErrorException $e) {
      $message = $e->getMessage();
      $code = 400;
      DB::connection('tenant')->rollBack();
    }    

    return response()->json(['message' => $message], $code);
  }

  public function destroy($id)
  {
    $compra = Compra::findOrfail($id);
    $isNotValid = false;

    if ($isNotValid) {
      notificacion('Acción ', 'No se puede eliminar esta compra debido ha que la caja donde se registro esta cerrada', 'error');
      return back();
    }

    event(new CompraDeleting($compra));

    notificacion('Acción exitosa', 'Se ha eliminado la compra correctamente');
    return back();
  }

  public function pdf($id)
  {
    $compra = Compra::find($id);
    $data = $compra->dataPdf();
    $pdf = new PDFGenerator(view('pdf.compras.default.a4_1', $data), PDFGenerator::HTMLGENERATOR);
    $pdf->generator->setGlobalOptions(PDFGenerator::getSetting(PDFPlantilla::FORMATO_A4, PDFGenerator::HTMLGENERATOR));
    $pdf->generator->generate();
  }
}