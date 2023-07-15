<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\ActiveEmpresaTenant;
use Hyn\Tenancy\Database\Connection;

class EmpresaLocalController extends Controller
{
  public function consultLocals(Request $request )
  {
    $empresa = Empresa::find($request->input('empresa_id'));
    
    (new ActiveEmpresaTenant($empresa))->handle();

    return $empresa->locales->map(function($local){
      return [
        'id' => $local->LocCodi,
        'name' => $local->LocNomb,
        'direccion' => $local->LocDire,
      ];
    });
  }
}
