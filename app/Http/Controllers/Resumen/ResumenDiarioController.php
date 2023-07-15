<?php

namespace App\Http\Controllers\Resumen;

use App\Http\Controllers\Controller;
use App\Resumen;

class ResumenDiarioController extends Controller
{
  public function index()
  {
    return view('resumen.index');
  }

  public function txt($id)
  {
    $resumen = Resumen::find($id);
    // dd( $resumen , $id );
    $txt = $resumen->getTextSunat();
    $name = $resumen->nameFile(false, '.txt');
    $path = fileHelper()->saveTemp($txt, $name);
    return response()->download($path);
  }

  public function validar($id)
  {
    $resumen = Resumen::find($id);
    notificacion('Validacion exitosa');
    $resumen->saveSuccessValidacion("0");
    return back();
  }


}
