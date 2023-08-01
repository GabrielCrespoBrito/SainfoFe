<?php

namespace App\Http\Controllers\Suscripcion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Suscripcion\Suscripcion;

class SuscripcionController extends Controller
{
  public function show()
  {
    $suscripcion = get_empresa()->suscripcionActual();
    return view('suscripcion.show', compact('suscripcion'));
  }
}
