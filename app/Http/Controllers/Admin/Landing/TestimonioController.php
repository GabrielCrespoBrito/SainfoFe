<?php

namespace App\Http\Controllers\Admin\Landing;

use Illuminate\Http\Request;
use App\Admin\Models\Testimonio;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\ClienteRequest;
use App\Http\Requests\Landing\TestimonioRequest;

class TestimonioController extends Controller
{
  public function index()
  {
    return view('admin.pagina.testimonios.index');
  }

  public function search()
  {
    return response()->json([
      'models' => Testimonio::allWithLogoPath()
    ]);
  }

  public function store( TestimonioRequest  $request)
  {
    DB::beginTransaction();
    try {
      $testimonio = new Testimonio();
      $testimonio->representante = $request->representante;
      $testimonio->cliente_id = $request->cliente_id;
      $testimonio->cargo = $request->cargo;
      $testimonio->testimonio_text = $request->testimonio_text;
      $testimonio->url_video = $request->url_video;
      $testimonio->imagen = $testimonio->uploadImage($request->file('imagen'), $testimonio->getImageName());
      $testimonio->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json([
        'message' => $th->getMessage()
      ], 400);
    }

    return [
      'message' => 'Testimonio Guardado Exitosamente'
    ];
  }

  public function update(TestimonioRequest $request, $id)
  {
    DB::beginTransaction();
    try {
      
      $testimonio = Testimonio::find($id);
      $testimonio->representante = $request->representante;
      $testimonio->cliente_id = $request->cliente_id;
      $testimonio->cargo = $request->cargo;
      $testimonio->testimonio_text = $request->testimonio_text;
      $testimonio->url_video = $request->url_video;
      $file = $request->file('imagen');
      if ($file) {
        $testimonio->deleteImage();
        $testimonio->imagen = $testimonio->uploadImage($file, $testimonio->getImageName());
      }
      $testimonio->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['message' => $th->getMessage() ], 400);
    }

    return [
      'message' => 'Cliente Guardado Exitosamente'
    ];
  }


  public function delete($id)
  {
    $cliente = Testimonio::find($id);
    $cliente->deleteImage();
    $cliente->delete();
    
    return response()->json([
      'message' => 'Eliminacion Exitosa'
    ]);
  }
}
