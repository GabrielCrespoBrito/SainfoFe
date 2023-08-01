<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\Empresa\EmpresaHasCreated;
use App\Http\Requests\EmpresaCreateRequest;
use App\Jobs\Empresa\DeleteAllForFailCreation;
use App\Http\Requests\Empresa\UpdateBasicRequest;
use App\Http\Controllers\Empresa\EmpresaMainController;

class EmpresaController extends EmpresaMainController
{
  public $view_edit = 'admin.empresa.edit';
  public $is_area_admin = true;

  public function __construct()
  {
    $this->middleware('isAdmin');
  }

  public function updateDataBasicEscritorio(UpdateBasicRequest $request, $id)
  {
    $empresa = Empresa::find($id);
    $data = $request->only('nombre_comercial', 'direccion', 'ubigeo', 'departamento', 'provincia', 'distrito', 'email', 'telefonos', 'rubro', 'active', 'venc_certificado', 'fecha_suscripcion');
    $empresa->EmpLin2 = $data['direccion'];
    $empresa->EmpLin3 = $data['email'];
    $empresa->EmpLin4 = $data['telefonos'];
    $empresa->EmpLin5 = $data['nombre_comercial'];
    $empresa->EmpLin6 = $data['rubro'];
    $empresa->venc_certificado = $data['venc_certificado'];
    // $empresa->end_plan = $data['fecha_suscripcion'];
    if(isset($data['ubigeo'])){
      $empresa->setUbigeo($data['ubigeo']);
    }
    $empresa->save();
    $empresa->cleanCache();
    noti()->success('Acción exitosa', 'Se ha modificado exitosamente la información de la empresa');
    return back();
  }

  public function store(EmpresaCreateRequest $request)
  {

    // _dd( $request->all() );
    // exit();

    $message = 'Se ha creado exitosamente la empresa';
    $type = "success";
    // route('usuarios.mantenimiento');
    $success = true;
    $status = 200;
    $empresa = null;
    try {
      DB::beginTransaction();
      $empresa = Empresa::saveWeb($request->all());
      $user = User::find( $request->usuario );
      $user->asociateToEmpresa($empresa->empcodi, false);
      DB::commit();
    } catch (\Exception | \Throwable $th) {
      throw $th;
      $success = false;
      $status = 400;
      $type = "error";
      $message = "Error al Guardar " . $th->getMessage();
      DeleteAllForFailCreation::dispatchNow($empresa);
    }

    if ($success) {
      DB::commit();
      notificacion('', $message, $type);
    }
    
    $route =  route('admin.empresa.edit_basic', ['id' => $empresa->id()]);
    return response()->json(['message' => $message, 'route' => $route], $status);
  }


  public function documentosReporte($empresa_id)
  {
    $empresa = Empresa::find($empresa_id);

    return view('admin.reportes.ventas_mensual', ['empresa_id' => $empresa_id]);
  }
}