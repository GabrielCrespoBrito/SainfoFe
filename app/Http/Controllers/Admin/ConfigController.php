<?php

namespace App\Http\Controllers\Admin;

use App\SettingSystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ConfigController extends Controller
{
  public function __construct()
  {
    $this->middleware('isAdmin');
  }

  public function index()
  {
    $configuraciones = SettingSystem::all();
    return view('admin.configuraciones.index', ['configuraciones' => $configuraciones]);
  }

  public function exeCode($code)
  {
    
     #Code here ............
  }


  public function store(Request $request)
  {
    foreach ($request->all() as $id  => $value) {
      if (is_int($id) && !is_null($value)) {
        SettingSystem::find($id)->update(['value' => $value]);
      }
    }

    Cache::forget('settings');
    noti()->success('Configuracion guardada exitosamente');
    return redirect()->back();
  }

}
