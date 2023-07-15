<?php

namespace App\Http\Controllers\Empresa;


class EmpresaController extends EmpresaMainController 
{
  public function __construct()
  {
    $this->is_area_admin = false;    
    $this->view_edit = 'empresa.edit_current';
    $this->middleware(p_midd('A_PARAMETRO', 'R_EMPRESA'));
  }
} 