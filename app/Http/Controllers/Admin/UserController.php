<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Local;
use App\Empresa;
use App\SerieDocumento;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Events\User\UserOwnerHasCreated;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use Spatie\Permission\Models\Permission;
use App\Repositories\UserRepositoryInterface;
use App\Http\Requests\UsuarioAsignarDocumentoStoreRequest;

class UserController extends Controller
{
  private $repository;

  public function __construct(UserRepositoryInterface $repository)
  {
    // $this->middleware('acceso_owner')->only(['index', 'storeOwner', 'permisos', 'storePermisos']);

    // $this->middleware('isAdmin')->only(['mantenimiento', 'roles', 'seleccionar_empresa', 'store_empresa', 'empresa_delete']);

    // // Comprobar que el registro de usuarios este activo
    // $this->middleware('registration_user.active')->only(['store']);

    // $this->middleware("check.consumo:usuarios")->only(['storeOwner']);

    $this->repository = $repository;
  }

  /**
   * Mostrar los usuarios de una empresa
   *
   * @return view
   */
  public function index()
  {
    $empresa = get_empresa();
    $users = $empresa->users;
    // dd( $users );
    return view('users.index', compact('users'));
  }

  public function search(Request $request)
  {
    $busqueda = User::query()->with('roles');

    return \DataTables::of($busqueda)
      ->addColumn('empresas', function ($user) {
        if ($user->empresas->count()) {
          $str = "";
          foreach ($user->empresas as $e) {            
            $str .= sprintf('<a class="btn btn-block btn-default btn-xs" href="%s">%s</a>', 
              route('admin.usuario-empresa.show', ['id' => $e->id]),
              $e->empresa->nombre());
          }
        } 
        
        else {
          $str = sprintf('<a  class="btn btn-primary btn-xs" href="%s"> <span class="fa fa-plus"></span> Agregar</a>', route('admin.usuario-empresa.create', $user->id()));
        }

        return $str;
      })
      ->addColumn('roles', function ($user) {
        return $user->roles->pluck('name')->implode(' ');
      })
      ->addColumn('estado', 'admin.usuarios.partials.column_estado')
      ->addColumn('btn', 'admin.usuarios.partials.column_accion')
      ->rawColumns(['btn', 'estado', 'empresas'])
      ->make(true);
  }

  public function mantenimiento()
  {
    $roles = Role::all();
    return view('admin.usuarios.mantenimiento', compact('roles'));
  }

  public function showForm( Request $request, $id = null )
  {
    $model = $id ? User::find($id) : new User();
    $create = is_null($id);
    return view('users.form_admin', compact('model', 'create'));
  }


  public function create()
  {
    return view('usuarios.create');
  }

  public function storeOwner(UserStoreRequest $request)
  {
    $empresa = get_empresa();
    $data = $request->all();
    $data['verificate'] = 1;
    $user = $this->repository->create($data);
    $user->asociateToEmpresa($empresa->empcodi);
    event(new UserOwnerHasCreated($empresa, $data, $user));
  }

  public function store(UserStoreRequest $request)
  {
    $user = new User;
    $user->usucodi = User::ultimoCodigo();
    $user->usulogi = $request->usuario;
    $user->usunomb = $request->nombre;
    $user->carcodi = "01";
    $user->usucla2 = $request->password;
    $user->usutele = $request->telefono;
    $user->usudire = $request->direccion;
    $user->email   = $request->email;
    $user->active  = "0";
    $user->UDelete = "";
    $user->save();
    return ['redirect' => true, 'url' => back()->getTargetUrl() ];
  }

  public function CheckPriv(Request $request)
  {
    $user = User::find($request->codigo);
  }

  // Seleccionar empresa  
  public function seleccionar_empresa($id_user = "all", $id_empresa = "all")
  {
    $emps = Empresa::all();
    $user = $id_user == "all" ? User::all() : User::findOrfail($id_user);
    $emps = $id_empresa == "all" ? Empresa::all() : Empresa::find($id_empresa);
    return view('admin.usuarios.seleccion_empresa.create', compact('user', 'emps', 'id_user', 'id_empresa'));
  }
  
  public function activeToggle(Request $request, $id_user)
  {
    $user = User::findOrfail($id_user);
    $state = $user->toggleActive();
    $state =  $state ? "Activo" : "Inactivo";
    noti()->success('Estado Actualizado', 'El usuario se ha actualizado exitosamente al estado ' . $state);
    return redirect()->route('admin.usuarios.index');
  }

  public function createDocumento( $id_empresa = "all", $id_user = "all", $id_local = "all" ) {
    $tipo_documentos = TipoDocumentoPago::validDocumentos();
    $users = $id_user == "all" ? User::all() : User::findOrfail($id_user);
    $locales = $id_local == "all" ? Local::where('empcodi', $id_empresa)->get() : Local::findOrfail($id_local);
    $empresas = $id_empresa == "all" ? Empresa::all() : Empresa::findOrfail($id_empresa);

    return view('usuarios.seleccion_documento.create', compact('id_local', 'id_user', 'id_empresa', 'empresas', 'locales', 'users', 'tipo_documentos'));
  }

  public function storeDocumento(UsuarioAsignarDocumentoStoreRequest $request)
  {
    $data = $request->except('_token');
    $local = Local::find($data["loccodi"]);
    $data["empcodi"] = $local->EmpCodi;
    $data['numcodi'] = agregar_ceros($data['numcodi'], 6, 0);
    $data['defecto'] = $request->defecto ? "0" : 1;
    $data['estado'] = $request->estado ? "0" : 1;

    SerieDocumento::create($data);
    noti()->success('Documento asignado correctamente', 'Se ha registrado correctamente');

    return redirect()->route('empresa.mantenimiento');
  }

  public function update(UserUpdateRequest $request)
  {
    $user = User::findOrfail($request->id);

    $user->usunomb = $request->nombre;
    if (!is_null($request->password)) {
      $user->usucla2 = $request->password;
    }
    $user->usutele = $request->telefono;
    $user->usudire = $request->direccion;
    $user->email   = $request->email;
    $user->save();

    return $user;
  }

  public function permisos($user_id)
  {
    $user = User::findOrfail($user_id);
    $permisos_group = Permission::where('is_admin', 0)->get()->groupBy('group');

    return view('users.permisos', compact('user', 'permisos_group'));
  }

  public function storePermisos(Request $request, $user_id)
  {
    $user = User::findOrfail($user_id);
    $user->syncPermissions($request->permisos);
    noti()->success('Permisos asignados', "Se ha establecido exitosamente los permisos para el usuario {$user->nombre()}");
    return redirect()->route('usuarios.index');
  }

  public function delete(UserDeleteRequest $request, $id_user)
  {
    $user = User::findOrfail($id_user);
    foreach ($user->empresas as $empresa) {
      $empresa->delete();
    }
    noti()->success('Usuario borrado', 'Se ha borrado correctamente');
    return redirect()->route('admin.usuarios.index');
  }

  
}
