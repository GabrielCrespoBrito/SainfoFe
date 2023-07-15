<?php

namespace App\Http\Controllers;

use App\ClienteProveedor;
use App\User;
use App\Local;
use Exception;
use App\Empresa;
use App\Permission;
use App\UserEmpresa;
use App\SerieDocumento;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserStoreRequest;
use App\Events\User\UserOwnerHasCreated;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\User\VerificateUser;
use App\Repositories\UserRepositoryInterface;
use App\Http\Requests\UserEmpresaCreateRequest;
use App\Http\Requests\UserEmpresaDeleteRequest;
use App\Http\Requests\UsuarioAsignarDocumentoStoreRequest;
// use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
  use VerificateUser;

  private $repository;

  public function __construct( UserRepositoryInterface $repository )
  { 
    $this->middleware('acceso_owner')->only(['index', 'storeOwner', 'permisos', 'storePermisos', 'showForm' ]);

    $this->middleware('isAdmin')->only(['mantenimiento', 'roles', 'seleccionar_empresa', 'store_empresa', 'empresa_delete']);

    // Comprobar que el registro de usuarios este activo
    $this->middleware('registration_user.active')->only(['store']);

    $this->middleware("check.consumo:usuarios")->only(['storeOwner']);
    
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
    return view('users.index', compact('users'));
  }

  public function search( Request $request)
  {
    $busqueda = User::query()->with('roles');
    return \DataTables::of($busqueda)
    ->addColumn('empresas', function($user){

      if($user->empresas->count()){
        $str = "";
        foreach( $user->empresas as $e ){
          $str .= '<a  class="btn btn-block btn-default btn-xs" href="'  . 
          route('usuarios.empresa.show' , [ 'id' => $e->id ] ) .
            '">' . $e->empresa->nombre() . '</a>';
        }
      }

      else {
        $str =         
        '<a  class="btn btn-primary btn-xs" href="'  . 
        route('usuarios.empresa.create' , $user->id()) .
        '"> <span class="fa fa-plus"></span> Agregar  ' .
        '</a>';
      }
      return $str;
    })   
    ->addColumn( 'roles' , function($user){
      return $user->roles->pluck('name')->implode(' ');
    })
    ->addColumn('estado', 'usuarios.partials.column_estado')       
    ->addColumn('btn', 'usuarios.partials.column_accion')
    ->rawColumns(['btn','estado','empresas'])    
    ->make(true);
  }

  public function mantenimiento()
  {
    $roles = Role::all();
    return view('usuarios.mantenimiento' , compact('roles'));
  }

  public function roles( $id_user )
  {
    $user = User::find($id_user);
    $roles = Role::all();
    return view('usuarios.assign_role' , compact( 'user' , 'roles'));    
  }

  public function roles_store( Request $request , $id_user )
  {
    $user = User::find($id_user);
    $user->syncRoles($request->roles);
    noti()->success('Roles asignados' , 'Se ha agregado/remove los roles al usuario correctamente');
    return redirect()->route('usuarios.mantenimiento');
  }


  public function create()
  {
  	$users = User::all();
    return view('usuarios.create');
  }

  public function storeOwner( UserStoreRequest $request )
  {
    $empresa = get_empresa();
    $data = $request->all();
    $data['verificate'] = 1;
    $user = $this->repository->create( $data );   
    $user->asociateToEmpresa( $empresa->empcodi, false, [], false );
    
    if ($request->permisos) {
      $user->registerPermissions($request->permisos);
    }

    if ($request->local) {
      $user->registerLocales($request->local);
    }    
  }

  public function store( UserStoreRequest $request )
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
    $user->asociateToEmpresa(empcodi(), false, [], false);

    if($request->permisos){
      $user->registerPermissions($request->permisos);
    }

    if ($request->local) {
      $user->registerLocales($request->local);
    }

    $url = route('usuarios.empresa.create' , $user->usucodi );
    return ['redirect' => true , 'url' => $url ];
  }

  public function CheckPriv( Request $request )
  {
    $user = User::find( $request->codigo );
  }

  // Seleccionar empresa  
  public function seleccionar_empresa( $id_user = "all" , $id_empresa = "all" )
  {
    $emps = Empresa::all();
    $user = $id_user == "all" ? User::all() : User::findOrfail($id_user);        
    $emps = $id_empresa == "all" ? Empresa::all() : Empresa::find($id_empresa);
    // dd($id_empresa , $emps );
    return view('usuarios.seleccion_empresa.create' , 
    compact('user', 'emps' , 'id_user' , 'id_empresa'));
  }

  public function store_empresa( UserEmpresaCreateRequest $request )
  {
    $user_empresa = new UserEmpresa;
    $user_empresa->usucodi = $request->id_user;
    $user_empresa->empcodi = $request->id_empresa;
    $user_empresa->save();
    $user_empresa->assignToDefaultLocal();
    $user_empresa->createDefaultCaja();
    \Cache::flush();

    noti()->success('Usuario agregado' , 'Se ha agregado el usuario a la empresa');

    return redirect()->route('usuarios.mantenimiento');    
  }

  public function empresa_show( $id )
  {
    $user_empresa = UserEmpresa::with(['user','empresa'])->find($id);
    $user = $user_empresa->user;
    $empresa = $user_empresa->empresa;

    return view('usuarios.seleccion_empresa.show' , [
      'user'    => $user, 
      'empresa' => $empresa,
      'id'      => $id,
    ]);
  }

  public function empresa_delete( UserEmpresaDeleteRequest $request , $id )
  {
    $user_empresa = UserEmpresa::findOrfail($id);
    $user_empresa->delete();
    noti()->success('Borrado exitoso' , 'Se ha Eliminado exitosamente la información asociada de este usuario a la empresa');
    return redirect()->route('usuarios.mantenimiento');
  }

  public function activeToggle( Request $request , $id_user )
  {
    $user = User::findOrfail($id_user);
    $state = $user->toggleActive();
    $state =  $state ? "Activo" : "Inactivo";
    noti()->success('Estado Actualizado' , 'El usuario se ha actualizado exitosamente al estado ' . $state );
    return redirect()->route('usuarios.mantenimiento');
  }

  public function cambiarStatus($id_user)
  {
    $user = User::findOrfail($id_user);
    $state = $user->toggleActive() ? "Activo" : "Inactivo" ;
    noti()->success('Estado Actualizado', 'El usuario se ha actualizado exitosamente al estado ' . $state);
    return redirect()->back();
  }

  

  public function createDocumento( 
    $id_empresa = "all" , 
    $id_user = "all" , 
    $id_local = "all" 
    )
  {
    $tipo_documentos = TipoDocumentoPago::validDocumentos();
    $users = $id_user == "all" ? User::all() : User::findOrfail($id_user);        
    $locales = $id_local == "all" ? Local::where('empcodi',$id_empresa)->get() : Local::findOrfail($id_local);
    $empresas = $id_empresa == "all" ? Empresa::all() : Empresa::findOrfail($id_empresa);

    return view(
      'usuarios.seleccion_documento.create', 
      compact('id_local','id_user','id_empresa','empresas','locales','users', 'tipo_documentos')
    );            
  }

  public function storeDocumento( UsuarioAsignarDocumentoStoreRequest $request )
  {
    $data = $request->except('_token');
    $local = Local::find($data["loccodi"]);
    $data["empcodi"] = $local->EmpCodi;
    $data['numcodi'] = agregar_ceros($data['numcodi'] , 6 , 0);
    $data['defecto'] = $request->defecto ? "0" : 1;
    $data['estado'] = $request->estado ? "0" : 1; 

    SerieDocumento::create($data);
    noti()->success('Documento asignado correctamente' , 'Se ha registrado correctamente');

    return redirect()->route('empresa.mantenimiento');
  }

  public function update( UserUpdateRequest $request )
  {
    $user = User::findOrfail( $request->id );

    $user->usunomb = $request->nombre;

    if( !is_null($request->password) ){
      $user->usucla2 = $request->password;
    }
    $user->usutele = $request->telefono;
    $user->usudire = $request->direccion;
    $user->email   = $request->email;
    $user->save();
 
    $user->registerLocales($request->local, true);
    
    return $user;
  }

  public function updateOwner(UserUpdateRequest $request)
  {
    $user = User::findOrfail($request->id);

    if( $user->isAdmin() ){
      throw new Exception("Error", 1);
      return;
    }

    $user->usunomb = $request->nombre;
    if (!is_null($request->password)) {
      $user->usucla2 = $request->password;
    }
    $user->usutele = $request->telefono;
    $user->usudire = $request->direccion;
    $user->email   = $request->email;
    $user->save();


    $user->registerLocales($request->locales, true);
    _dd("hola mundo owner");
    exit();

    return $user;
  }

  public function delete( UserDeleteRequest $request ,$id_user )
  {
    $user = User::findOrfail($id_user);

    $user->deleteAll();

    noti()->success('Usuario borrado','Se ha borrado correctamente');
    return redirect()->route('usuarios.index');    
  }


  public function permisos( $user_id )
  {
    $user = User::findOrfail($user_id);
    $permisos_group = Permission::where('is_admin' , 0)->get()->groupBy('group');
    return view('users.permisos', compact('user', 'permisos_group'));
  }

  public function storePermisos( Request $request, $user_id )
  {
    $user = User::findOrfail($user_id);
    $user->syncPermissions($request->permisos);
    // notificacion('Permisos asignados', "Se ha establecido exitosamente los permisos para el usuario {$user->nombre()}");
    noti()->success('Permisos asignados', "Se ha establecido exitosamente los permisos para el usuario {$user->nombre()}");
    return redirect()->route('usuarios.index');
  }


  public function showForm( $id = null )
  {
    $model = $id ? User::find($id) : new User();
    $create = is_null($id);
    $isAdmin = true;
    $locales = Local::all();
    $user_locales = $id ? $model->locales : null;
    // 
    $permisos = $id ? null : Permission::where('is_admin' , 0)
    ->get()
    ->groupBy('group')
    ->keys()
    ->map(function($item,$index){
      return [ 'id' => $item,  'nombre' => __( 'messages.'. $item )];
    });

    return view('users.form' , compact('model', 'create', 'isAdmin', 'locales' , 'user_locales', 'permisos'));
  }

}