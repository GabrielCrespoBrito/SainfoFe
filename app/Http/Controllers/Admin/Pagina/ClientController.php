<?php

namespace App\Http\Controllers\Admin\Pagina;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
  public function index(){
    return view('admin.pagina.clientes.index');
  }
}
