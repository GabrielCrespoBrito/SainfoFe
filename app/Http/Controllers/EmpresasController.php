<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\EmpresaOpcion;
use Illuminate\Http\Request;
use App\Http\Requests\SubirCert;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use App\Events\Empresa\EmpresaHasCreated;
use App\Http\Requests\SaveEmpresaRequest;
use App\Http\Requests\EmpresaCreateRequest;
use App\Jobs\Empresa\DeleteForFailCreation;
use App\Http\Requests\SubirCertificadoRequest;
use App\Jobs\Empresa\DeleteAllForFailCreation;
use App\Events\Empresa\CertFacturacionInfoSave;
use App\Http\Requests\Empresa\DeleteRequest;
use App\Http\Requests\Empresa\EmpresaCertRequest;
use App\Http\Requests\Empresa\EmpresaUpdateRequest;
use App\Http\Requests\Empresa\EmpresaUpdateParametroBasicRequest;
use App\Http\Requests\Empresa\EmpresaSaveDefaultInformationDefaultRequest;
use App\Http\Requests\EmpresaOptionsRequest;

class EmpresasController extends Controller
{
  public $empresa;

  public function __construct()
  {
    $this->empresa = new Empresa();
    $this->middleware('isAdmin')->only(['updateUsos']);
    $this->middleware(p_midd('A_PARAMETRO', 'R_EMPRESA'))->only('edit', 'update', 'parametros_principales');
  }

  public function search(Request $request)
  {
    $status = $request->input( 'status'  , 1);
    $busqueda = Empresa::where( 'active' , $status );
    return \DataTables::of( $busqueda )
      ->addColumn( 'estado', 'empresa.partials.column_estado' )
      ->addColumn( 'accion', 'empresa.partials.column_accion' )
      ->rawColumns([ 'accion', 'estado' ])
      ->make(true);
  }

  public function index()
  {
    return view('empresa.index', ['roles' => Role::all()]);
  }

  public function create()
  {
    return view('empresa.create', ['empresa' => $this->empresa]);
  }

  public function store(EmpresaCreateRequest $request)
  {
    $message = 'Se ha creado exitosamente la empresa';
    $type = "success";
    $route = route('usuarios.mantenimiento');
    $success = true;
    $status = 200;
    $empresa = null;

    try {
      \DB::beginTransaction();
      $empresa = Empresa::saveData($request->all(), null, true);
      event(new EmpresaHasCreated($empresa));
      \DB::commit();
    } catch (\Exception |  \Throwable $th) {
      throw $th;
      $success = false;
      $status = 400;
      $type = "error";
      $route = "aaa";
      $message = "Error al Guardar " . $th->getMessage();
      DeleteAllForFailCreation::dispatchNow($empresa);
    }

    if ($success) {
      \DB::commit();
      notificacion('', $message, $type);
    }

    return response()->json(['message' => $message, 'route' => $route], $status);
  }

  public function parametros_principales()
  {
    $empresa = get_empresa();
    if (!auth()->user()->isAdmin()) {
      return redirect()->route('empresa.edit');
    }

    $parametros = $settings =  $empresa->opcion;
    $has_option = $empresa->hasOption();

    if (!$has_option) {
      $empresa_default = Empresa::first();
      $parametros = $empresa_default->opcion;
    }
    $notFieldShow = EmpresaOpcion::NOTFIELDSHOW;
    $parametros = $parametros->toArray();

    return view('empresa.parametros', [
      'empresa' => $empresa,
      'parametros' => $parametros,
      'settings' => $settings,
      'has_option' => $has_option,
      'notFieldShow' => $notFieldShow
    ]);
  }

  public function store_parametros_principales(SaveEmpresaRequest $request)
  {
    $empresa = get_empresa();
    $data = $request->all();

    Empresa::saveData($data, $empresa);

    // Guardar imagen del logo
    if (isset($data['logo_secundario'])) {
      $empresa->storeLogo(Empresa::LOGO_TICKET);
    }

    notificacion('Actualización realizada', 'Se ha actualizado exitosamente la información de la empresa');
    return redirect()->back();
  }

  public function subirCertificado($id)
  {
    $empresa = Empresa::findByCodi($id);
    return view('empresa.subir_certificado', compact('empresa'));
  }

  public function storeCertificado(SubirCert $request, $id)
  {
    $empresa = Empresa::find($id);
    $fileHelper = FileHelper($empresa->EmpLin1);
    // 
    if ($request->cert_key) {
      $fileHelper->save_cert('.key', file_get_contents($request->cert_key->path()));
    }
    if ($request->cert_cer) {
      $fileHelper->save_cert('.cer', file_get_contents($request->cert_cer->path()));
    }
    if ($request->cert_pfx) {
      $fileHelper->save_cert('.pfx', file_get_contents($request->cert_pfx->path()));
    }

    notificacion('Se ha subido el archivo exitosamente', '');
    return redirect()->back();
  }

  public function checkCertificado(Request $request, $id)
  {
    $empresa = Empresa::find($id);
    $fileHelper = FileHelper($empresa->EmpLin1);

    $certs = [
      'key' => ['extention' => '.key', 'exists' => false],
      'cer' => ['extention' => '.cer', 'exists' => false],
      'pfx' => ['extention' => '.pfx', 'exists' => false],
    ];

    foreach ($certs as $cert => &$data) {
      $data['exists'] = $fileHelper->certExist($data['extention']);
    }

    session()->flash('checkCertificado', $certs);
    return redirect()->back();
  }

  public function store_option(EmpresaOptionsRequest $request, $id)
  {
    $name_cache = "option_empresa" . $id;
    \Cache::forget($name_cache);
    \Artisan::call('config:clear');
    $empresa = Empresa::find($id);
    $data = $request->except('_token', 'UltCpra', 'EmpCodi');;
    $data['EmpCodi'] = $id;

    if ($empresa->hasOption()) {
      $opcion = $empresa->opcion;
      $opcion->fill($data);
      if ($opcion->isDirty()) {
        $opcion->save();
      }
    } else {
      $data['UltCpra'] =  EmpresaOpcion::lastUltCpra();
      EmpresaOpcion::create($data);
    }


    notificacion('Acciòn exitosa', 'Se han guardado exitosamente las configuraciones de la empresa');
    return redirect()->route('home');
  }

  public function deleteLogo($id_empresa, $logo, Request $request)
  {
    $empresa = Empresa::find($id_empresa);

    $empresa->deleteLogo($logo);

    // if ($logo == "1") {
    //   $empresa->EmpLogo = null;
    // } elseif ($logo == "2") {
    //   $empresa->EmpLogo1 = null;
    // } elseif ($logo == "3") {
    //   $empresa->EmpDWeb = null;
    // } elseif ($logo == "4") {
    //   $empresa->FE_RESO = null;
    // }
    // $empresa->save();
    
    $empresa->cleanCache();

    noti()->success('Se ha eliminado exitosamente el logo');
    return redirect()->back();
  }



  public function saveInformacionDefecto(EmpresaSaveDefaultInformationDefaultRequest $request, $id)
  {
    $empresa = Empresa::find($id);
    $message = "Guardada información por defecto exitosamente";
    $type = "success";
    try {
      \DB::beginTransaction();
      $empresa->saveInformacionDefecto();
    } catch (\Exception |  \Throwable $th) {
      $message = "Error al guardar " . $th->getMessage();
      $type = "error";
      \DB::rollback();
    }

    notificacion('', $message, $type);

    return back();
  }


  /**
   * Modificar la información de la empresa actual del usuario
   *
   */
  public function edit()
  {
    // WhatAScamBrother
    $empresa = get_empresa();
    return view('empresa.edit_current', [
      'empresa' => $empresa,
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

    // notificacion('Acción exitosa', 'Actualización de tipo de cambio realizadaa');
    notificacion('Accion completada', 'Se ha actualizado los parametros de la empresa correctamente', 'success');
    // return redirect()->route('home');
    return back();
  }

  /**
   * Guardar información sobre el certificado y su clave sol
   * 
   * @return Json
   */
  public function certStore(SubirCertificadoRequest $request,  $empresa_id)
  {

    $empresa = Empresa::find($empresa_id);
    event(new CertFacturacionInfoSave($empresa, $request));
    $empresa->cleanCache();
    return response()->json(['message' => 'Información guardada exitosamente']);
  }



  public function configFinal()
  {
    return view('empresa.config', ['empresa' => get_empresa()]);
  }

  public function updateUsos($empresa_id)
  {
    $empresa = Empresa::find($empresa_id);
    $empresa->updateUsos();
    notificacion('Accion exitosa', 'Se ha actualizado los valores de la suscripción de la empresa');
    return back();
  }

  public function updatePreciosProductos()
  {
    abort_if(!auth()->user()->isAdmin(), 300);

    ini_set('max_execution_time', 240);

    get_empresa()->updatePreciosProductos();

    notificacion('Actualización exitosa', 'La Actualización de precios ha sido ejecutada satisfactoriamente');
    return redirect()->route('home');
  }

  public function updateValorVenta()
  {
    abort_if(!auth()->user()->isAdmin(), 300);

    ini_set('max_execution_time', 240);

    get_empresa()->updateValorVenta();

    notificacion('Actualización exitosa', 'La Actualización de precios ha sido ejecutada satisfactoriamente');
    return redirect()->route('home');
  }


  /*

  public function updateCampoCostos( $id = null )
  {
    abort_if(!auth()->user()->isAdmin(), 300);

    ini_set('max_execution_time', 600);

    get_empresa()->cambiarCampoCostos();

    notificacion('Actualización exitosa', 'La Actualización del campo de costo ha sido ejecutado satisfactoriamente');
    return redirect()->route('home');
  }

  */






  public function delete(DeleteRequest $request,  $empresa_id)
  {
    ini_set('max_execution_time', 240);
    $empresa = Empresa::find($empresa_id);
    \DB::beginTransaction();
    try {
      $empresa->deleteInfoInDatabasePrincipal();
      $empresa->deleteForceDatabase();
      $empresa->delete();
      \DB::commit();
    } catch (\Throwable $th) {
      \DB::rollback();
      notificacion('Error', "Hubo un inconveniente borrando la información de la empresa: {$th->getMessage()}", 'error');
      return redirect()->back();
    }

    notificacion('Actualización exitosa', 'La Actualización de precios ha sido ejecutada satisfactoriamente');
    return redirect()->back();
  }
}
