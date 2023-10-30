<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;

class PDFPlantillaController extends Controller
{
  public function generate( Request $request, $empresa_id, $plantilla_id)
  {
    $empresa = Empresa::find($empresa_id);
    empresa_bd_tenant($empresa->id());
    $route = $empresa->generatePlantillaPDF($plantilla_id);
    return response()->json([
      'success' => '',
      'route' => $route,
    ]);
  }
}