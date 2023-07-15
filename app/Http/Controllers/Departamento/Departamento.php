<?php

namespace App\Http\Controllers\Departamento;

use App\Departamento as DepartamentoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Departamento extends Controller
{

  public function departamentoConsulta( Request $request )
  {
    $this->authorize('departamento_consulta clientes');

    $departamento = DepartamentoModel::findOrfail( $request->id_departamento );    
    $provincias   = $departamento->provincias;
    $distritos   = $departamento->distritos;

    $data = [
      'provincias' => $provincias,
      'distritos'  => $distritos,
    ];

    return $data;
  }
}
