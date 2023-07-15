<?php

namespace App\Http\Controllers\Admin\Landing;

use Illuminate\Http\Request;
use App\Admin\Models\Testimonio;
use App\Admin\Models\ContCaracteristica;
use App\Admin\Models\TestimonioContabilidad;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\ClienteRequest;
use App\Http\Requests\Landing\TestimonioContRequest;
use App\Http\Requests\Landing\TestimonioRequest;

class ContabilidadTestiController extends Controller
{
  public function index()
  {
    $testimonios = TestimonioContabilidad::all();

    return view('admin.pagina.cont-testi.index', compact('testimonios'));
  }

  public function create()
  {
    $testimonio = new TestimonioContabilidad();
    return view('admin.pagina.cont-testi.create', compact('testimonio'));
  }



  public function edit($id)
  {
    $testimonio = TestimonioContabilidad::find($id);

    return view('admin.pagina.cont-testi.edit', compact('testimonio'));
  }


  public function store(TestimonioContRequest  $request)
  {
    DB::beginTransaction();
    try {
      $testimonio = new TestimonioContabilidad();
      $testimonio->representante = $request->representante;
      $testimonio->cargo = $request->cargo;
      $testimonio->testimonio_text = $request->testimonio_text;
      $testimonio->imagen = $testimonio->uploadImage($request->file('imagen'), $testimonio->getImageName());
      $testimonio->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json([
        'message' => $th->getMessage()
      ], 400);
    }

    noti()->success('Testimonio Guardado Exitosamente');
    return redirect()->route('admin.pagina.contabilidad-testi.index');
  }

  public function update(TestimonioContRequest $request, $id)
  {
    DB::beginTransaction();
    try {
      $testimonio = TestimonioContabilidad::find($id);
      $testimonio->representante = $request->representante;
      $testimonio->cargo = $request->cargo;
      $testimonio->testimonio_text = $request->testimonio_text;
      $file = $request->file('imagen');
      if ($file) {
        $testimonio->deleteImage();
        $testimonio->imagen = $testimonio->uploadImage($file, $testimonio->getImageName());
      }
      $testimonio->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['message' => $th->getMessage()], 400);
    }

    noti()->success('Testimonio Actualizado Exitosamente');
    return redirect()->route('admin.pagina.contabilidad-testi.index');
  }


  public function delete($id)
  {
    $testi = TestimonioContabilidad::findOrfail($id);
    $testi->deleteImage();
    $testi->delete();
    noti()->success('Testimonio Eliminado exitosamente');
    return back();
  }
}
