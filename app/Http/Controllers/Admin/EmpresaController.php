<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use App\Http\Controllers\Empresa\EmpresaMainController;

class EmpresaController extends EmpresaMainController
{
  public $view_edit = 'admin.empresa.edit';
  public $is_area_admin = true;

  public function __construct()
  {
    $this->middleware('isAdmin');
  }

  public function documentosReporte($empresa_id)
  {
    $empresa = Empresa::find($empresa_id);

    return view('admin.reportes.ventas_mensual', ['empresa_id' => $empresa_id]);
  }
}