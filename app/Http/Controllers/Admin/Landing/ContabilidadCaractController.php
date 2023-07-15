<?php

namespace App\Http\Controllers\Admin\Landing;

use Illuminate\Http\Request;
use App\Admin\Models\Testimonio;
use App\Admin\Models\ContCaracteristica;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\ClienteRequest;
use App\Http\Requests\Landing\ContabilidadCaractRequest;
use App\Http\Requests\Landing\TestimonioRequest;

class ContabilidadCaractController extends Controller
{
  public function index()
  {
    return view('admin.pagina.cont.index');
  }

  public function search()
  {
    return response()->json([
      'models' => ContCaracteristica::all()
    ]);
  }

  public function store(ContabilidadCaractRequest  $request)
  {
    DB::beginTransaction();
    try {
      $contCaract = new ContCaracteristica();
      $contCaract->nombre = $request->nombre;
      $contCaract->descripcion = $request->descripcion;
      $contCaract->icon = $request->icon;
      $contCaract->icon_color = $request->icon_color;
      $contCaract->card_color = $request->card_color;
      $contCaract->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json([
        'message' => $th->getMessage()
      ], 400);
    }

    return [
      'message' => 'Caracteristica Guardado Exitosamente'
    ];
  }

  public function update(ContabilidadCaractRequest $request, $id)
  {
    DB::beginTransaction();
    try {
      $contCaract = ContCaracteristica::find($id);
      $contCaract->nombre = $request->nombre;
      $contCaract->descripcion = $request->descripcion;
      $contCaract->icon = $request->icon;
      $contCaract->icon_color = $request->icon_color;
      $contCaract->card_color = $request->card_color;
      $contCaract->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['message' => $th->getMessage()], 400);
    }

    return [
      'message' => 'Caracteristica Actualizada Exitosamente'
    ];
  }


  public function delete($id)
  {
    ContCaracteristica::find($id)->delete();
    return response()->json([
      'message' => 'Eliminacion Exitosa'
    ]);
  }
}
