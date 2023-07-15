<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\UserLocal\UserLocal;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\UserLocal\UserLocalStoreRequest;

class UserLocalController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */  

  public $userlocal;
  
  public function __construct()
  {
    $this->userlocal = new UserLocal();
  }

  public function index(Request $request)
  {
    $empresas = Empresa::formatList();
    return view('admin.user_local.index', [
      'empresas' => $empresas
    ]);
  }

  /* 
    special-ilusion
    135.000 | ------------YoGanoEnUnParDeAños------------ USD$ months
  */

  public function search(Request $request)
  {
    $empresa_id = $request->input('empresa_id', '001');
    
    empresa_bd_tenant($empresa_id);
    $busqueda = UserLocal::with(['user', 'local'])
    ->where('empcodi', $empresa_id )
    ->get();

    return DataTables::of($busqueda)
    ->addColumn('column_accion', 'admin.user_local.partials.column_accion')
    ->addColumn('column_defecto', 'admin.user_local.partials.column_defecto')
    ->rawColumns(['column_accion', 'column_defecto'])
    ->make(true);
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create( Request $request )
  {

    $empresa = Empresa::find($request->input('empresa_id', '001'));
    empresa_bd_tenant($empresa->id());

    $users = $empresa->users;
    $locales = $empresa->locales;
    $userlocal = $this->userlocal;
    return view('admin.user_local.create', compact('users', 'locales', 'userlocal', 'empresa'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(UserLocalStoreRequest $request)
  {
    $empresa_id = $request->input('empresa_id');

    $data = [];
    $data['usucodi'] = $request->input('usucodi');
    $data['loccodi'] = $request->input('loccodi');
    $data['numcodi'] = "000000";
    $data['sercodi'] = "0000";
    $data['empcodi'] = $empresa_id;

    $data['defecto'] = 0;
    UserLocal::create($data);
    noti()->success('Usuario Local Creado');
    return redirect()->route('admin.user-local.index', ['empresa_id' => $empresa_id ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($usucodi, $loccodi)
  {
    $userlocal = $this->userlocal->find($usucodi, $loccodi);
    $empresa = Empresa::find($userlocal->empcodi);
    empresa_bd_tenant($userlocal->empcodi);
    $users = $empresa->users;
    $locales = $empresa->locales;

    return view('admin.user_local.edit', compact('users', 'locales', 'userlocal', 'empresa'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UserLocalStoreRequest $request, $usucodi, $loccodi)
  {
    $userlocal = $this->userlocal->find($usucodi, $loccodi);
    $previousDefecto = $userlocal->defecto;

    $data = [];
    $data['loccodi'] = $request->loccodi;

    try {
      DB::table('usuario_local')
        ->where('loccodi', $userlocal->loccodi)
        ->where('empcodi', $userlocal->empcodi)
        ->where('usucodi', $userlocal->usucodi)
        ->update($data);
    } catch (QueryException $e) {
      return back()->withErrors(['Usuario ya esta asociado ha este local', $e->getMessage()]);
    }

    noti()->success('Usuario local modificado exitosamente');
    return redirect()->route('admin.user-local.index');
  }

  /**
   * Poner local por defecto a un usuario especifico.
   * 
   */
  public function setDefaultLocal($usucodi, $loccodi)
  {
    resolve(UserLocal::class)
      ->cleanDefault($usucodi)
      ->setDefault($usucodi, $loccodi);

    noti()->success('Local por defecto asignado satisfactoriamente');
    return back();
  }

  public function consultLocals()
  {
    $user_locales = auth()->user()->locales->load('local');
    $locals = [];

    foreach ($user_locales as $user_local) {
      $locals[] = [
        'url' =>  route('user-local.default', ['usucodi' => $user_local->usucodi, 'loccodi' => $user_local->loccodi]),
        'descripcion' => optional($user_local->local)->LocNomb,
        'id' => optional($user_local->local)->LocCodi,
        'selected' => $user_local->defecto
      ];
    }

    return  response()->json(['locals' => $locals]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

   
  public function destroy($id)
  {

    list($usucodi, $local) = explode('-', $id);

    $empcodi = empcodi();

    DB::table('usuario_local')
    ->where('loccodi', $local)
      ->where('empcodi', $empcodi)
      ->where('usucodi', $usucodi)
      ->delete();

    DB::table('usuario_documento')
    ->where('loccodi', $local)
      ->where('empcodi', $empcodi)
      ->where('usucodi', $usucodi)
      ->delete();

    notificacion('', 'Registro eliminado exitosamente');
    return back();
  }
}
