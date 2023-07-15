<?php

namespace App\Http\Controllers\Cierre;

use App\Http\Controllers\Controller;
use App\Mes;

class CierreController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_CIERRE_MES', 'R_UTILITARIO'))->only('index', 'search');
  }

  public function index()
  {
    $busqueda = new Mes();
    $meses = $busqueda->load('cierre')->get()->sortByDesc('mescodi');
    return view('cierres.index', compact('meses'));
  }

  public function toggle($mescodi)
  {
    Mes::find($mescodi)->toggleCierre();;
    noti()->success('Acción Exitosa');
    return back();
  }

  public function search()
  {
  }
}
