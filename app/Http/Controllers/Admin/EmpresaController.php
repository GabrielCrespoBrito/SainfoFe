<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\Empresa\EmpresaHasCreated;
use App\Http\Requests\EmpresaCreateRequest;
use App\Http\Requests\Empresa\DeleteRequest;
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
    $data = $request->only('nombre_comercial', 'direccion', 'ubigeo', 'departamento', 'provincia', 'distrito', 'email', 'codigo', 'telefonos', 'rubro', 'active', 'venc_certificado', 'emis_certificado', 'fecha_suscripcion');

    $empresa->EmpLin2 = $data['direccion'];
    $empresa->EmpLin3 = $data['email'];
    $empresa->EmpLin4 = $data['telefonos'];
    $empresa->EmpLin5 = $data['nombre_comercial'];
    $empresa->EmpLin6 = $data['rubro'];
    $empresa->venc_certificado = $data['venc_certificado'];
    $empresa->emis_certificado = $data['emis_certificado'];
    $empresa->codigo = $data['codigo'];
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

  public function sendEmailVenc($id)
  {
    $empresa = Empresa::find($id);
    $empresa->sendEmailVencSuscripcion();
    noti()->success( 'Accion Exitosa','Email Enviado Satisfactoriamente');
    return back();
  }

  public function sendEmailPorVenc($id)
  {
    $empresa = Empresa::find($id);
    $empresa->sendEmailPorVencSuscripcion();
    noti()->success('Accion Exitosa', 'Email Enviado Satisfactoriamente');
    return back();
  }


  public function delete(DeleteRequest $request,  $empresa_id)
  {
    ini_set('max_execution_time', 240);
    $empresa = Empresa::find($empresa_id);
    DB::beginTransaction();
    try {
      $empresa->deleteInfoInDatabasePrincipal();
      $empresa->deleteForceDatabase();
      $empresa->delete();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollback();
      noti()->error('Error', "Hubo un inconveniente borrando la información de la empresa: {$th->getMessage()}");
      return redirect()->back();
    }

    noti()->success('Accion Exitosa', 'Empresa Eliminada Exitosamente');
    return redirect()->back();
  }

}

