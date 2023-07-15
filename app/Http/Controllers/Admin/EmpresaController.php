<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Empresa\EmpresaMainController;

class EmpresaController extends EmpresaMainController
{
  public $view_edit = 'admin.empresa.edit';
  public $is_area_admin = true;

  public function __construct()
  {
    $this->middleware('isAdmin');
  }
}