<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use App\EmpresaOpcion;
use App\SettingSystem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Events\Empresa\EmpresaHasCreated;
use App\Http\Requests\EmpresaCreateRequest;
use App\Http\Requests\Empresa\DeleteRequest;
use App\Http\Controllers\Empresa\TiendaTrait;
use App\Http\Requests\EmpresaResetDataRequest;
use App\Jobs\Empresa\DeleteAllForFailCreation;
use App\Http\Controllers\Empresa\CertificateTrait;
use App\Http\Requests\Empresa\EmpresaUpdateRequest;
use App\Http\Requests\Empresa\EmpresaUpdateParametroBasicRequest;
use Carbon\Carbon;

abstract class EmpresaMainController extends Controller
{
  use
    BasicTrait,
    SunatTrait,
    ParametrosTrait,
    CertificateTrait,
    TiendaTrait,
    VisualTrait;

  public $empresa;
  public $is_area_admin;
  public $view_edit;

  public function __construct()
  {
    $this->empresa = new Empresa();
  }

  public function search(Request $request)
  {
    $status = $request->input('status', 1);
    $busqueda = Empresa::where('active', $status);
    $today = date('Y-m-d');
    $dias_vencimiento = config('app.recordatorio_venc_certificado');
    
    if( $tipo = $request->input('tipo') ){
      $busqueda->where('tipo', $tipo);
    }

    if ($venc_cert = $request->input('venc_certificado')) {
      
      $fechaVencimiento = new Carbon();
      $fechaVencimiento->addDays($dias_vencimiento);
      $fechaVencimiento = $fechaVencimiento->format('Y-m-d');

      if ($venc_cert == "vencidas") {
        $busqueda->where('venc_certificado', '<',  $today );
      }

      else if($venc_cert == "por_vencer"){
        $busqueda->where('venc_certificado', '<=', $fechaVencimiento)
        ->where('venc_certificado', '>',  $today  );
      }

      else if( $venc_cert == "activas" ){
        $busqueda->where('venc_certificado', '>', $fechaVencimiento );
      }

    }

    return DataTables::of($busqueda)
      ->addColumn('link', 'admin.empresa.partials.column_link')
      ->addColumn('estado', 'admin.empresa.partials.column_estado')
      ->addColumn('accion', 'admin.empresa.partials.column_accion')
      ->addColumn('fecha_vencimiento', 'admin.empresa.partials.column_fecha_vencimiento')
      ->addColumn('ambiente', 'admin.empresa.partials.column_ambiente')
      ->addColumn('reporte_documentos', 'admin.empresa.partials.column_documentos')
      ->addColumn('column_tipo', 'admin.empresa.partials.column_tipo')
      ->addColumn('column_cert', 'admin.empresa.partials.column_cert')
      ->rawColumns(['link', 'accion', 'estado', 'reporte_documentos', 'fecha_vencimiento', 'ambiente', 'column_tipo', 'column_cert'])
      ->make(true);
  }

  public function index()
  {
    return view('admin.empresa.index', ['roles' => Role::all()]);
  }

  public function create()
  {
    return view('admin.empresa.create', ['empresa' => $this->empresa]);
  }

  public function store(EmpresaCreateRequest $request)
  {
    _dd($request->all());
    exit();

    $message = 'Se ha creado exitosamente la empresa';
    $type = "success";
    $route = route('admin.empresas.index');
    $success = true;
    $status = 200;
    $empresa = null;
    try {
      DB::beginTransaction();
      $empresa = Empresa::saveData($request->all(), null, true);
      event(new EmpresaHasCreated($empresa));
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

    return response()->json(['message' => $message, 'route' => $route], $status);
  }

  public function edit($id)
  {
    $empresa = Empresa::find($id);
    empresa_bd_tenant($empresa->id());
    $empresa->load(['ubigeo.departamento', 'ubigeo.provincia']);
    $titulo = $empresa->nombre();
    $logos_dimenciones = config('app.logos_dimenciones');
    $settings = new EmpresaOpcion();
    $parametros = $empresa->opcion->getParameterValidToModify();
    $configuracion_igv = json_decode(get_setting(SettingSystem::CAMPO_CONFIGURACION_IGV));

    return view($this->view_edit, [
      'area_admin' => $this->is_area_admin,
      'empresa' => $empresa,
      'titulo' => $titulo,
      'settings' => $settings,
      'parametros' => $parametros,
      'configuraciones_igv' => $configuracion_igv,
      'logos_dimenciones' => $logos_dimenciones
    ]);
  }

  /**
   * Modificar la información de la empresa actual del usuario
   *
   */
  public function update(EmpresaUpdateRequest $request)
  {
    $empresa = get_empresa();
    $data = $request->only('nombre_comercial', 'direccion', 'ubigeo', 'departamento', 'provincia', 'distrito', 'email', 'telefonos', 'rubro', 'logo_principal', 'logo_secundario', 'logo_subtitulo', 'logo_marca_agua', 'imprimir', 'nombre_impresora', 'cant_copias');
    Cache::forget('empresa' . $empresa->empcodi);
    Empresa::saveData($data, $empresa, false, true);

    // Guardar imagen del logo
    if (isset($data['logo_secundario'])) {
      $empresa->storeLogo(Empresa::LOGO_TICKET);
    }

    notificacion('Accion completada', 'Se ha actualizado la información de la empresa correctamente', 'success');
    return redirect()->route('home');
  }

  /**
   * Modificar la información de la empresa actual del usuario
   *
   */
  public function updateParametroBasic(EmpresaUpdateParametroBasicRequest $request, $id)
  {
    $empresa = get_empresa();
    Cache::forget('empresa' . $empresa->empcodi);
    $empresa->updateParametrosBasic($request);
    noti()->success('Accion completada', 'Se ha actualizado los parametros de la empresa correctamente');
    return back();
  }


  public function delete(DeleteRequest $request,  $empresa_id)
  {
    ini_set('max_execute_time', 240);
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

    noti()->success('Actualización exitosa', 'La Actualización de precios ha sido ejecutada satisfactoriamente');
    return redirect()->back();
  }

  public function deleteData(EmpresaResetDataRequest $request,  $empresa_id)
  {
    $empresa = Empresa::find($empresa_id);
    empresa_bd_tenant($empresa_id);
    $empresa->deleteData($request->eliminar);
    noti()->success('Actualización exitosa');
    return redirect()->back();
  }

  public function resetData(EmpresaResetDataRequest $request,  $empresa_id)
  {
    $empresa = Empresa::find($empresa_id);
    empresa_bd_tenant($empresa_id);
    $empresa->resetData();
    noti()->success('Actualización exitosa');
    return redirect()->back();
  }

  public function deleteLogo($id_empresa, $logo)
  {
    $empresa = Empresa::find($id_empresa);
    $empresa->deleteLogo($logo);
    noti()->success('Se ha eliminado exitosamente el logo');
    return redirect()->back();
  }

  public function changeStatus($id_empresa)
  {
    Empresa::find($id_empresa)->toggleEstado();
    noti()->success('Acción Exitosa');
    return redirect()->back();
  }

  public function changeAplicacionIGV(Request $request, $id_empresa)
  {
    empresa_bd_tenant($id_empresa);
    Empresa::find($id_empresa)->changeAplicacionIGV($request->input('aplicar_igv', '0'));
    noti()->success('Acción Exitosa');
    return redirect()->back();
  }

  public function logoFooterDefault($id_empresa)
  {
    $empresa = Empresa::find($id_empresa);
    $empresa->setLogoFooterSainfo();
    noti()->success('Se ha eliminado exitosamente el logo');
    return redirect()->back();
  }
}
