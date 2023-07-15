<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Local;
use App\Empresa;
use App\PDFPlantilla;
use App\SerieDocumento;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsuarioAsignarDocumentoStoreRequest;

class UserDocumentoController extends Controller
{
  public function search( Request $request )
  {
    empresa_bd_tenant( $request->input('empresa_id', '001') );

    $busqueda = SerieDocumento::with(['user','local','empresa'])
    ->where('empcodi' , empcodi());

    if($loccodi = $request->input('local_id')){
      $busqueda = $busqueda->where( 'loccodi' , $loccodi );
    }
    
    
    if ( $usucodi = $request->input('user_id') ) {
      session()->put('usucodi', $request->input('user_id'));
      $busqueda = $busqueda->where('usucodi', $usucodi);
    }

    return DataTables::of($busqueda)
    ->addColumn('acciones', 'admin.usuarios_documentos.partials.column_accion')
    ->rawColumns([ 'acciones' ])    
    ->make(true);    
  }

  public function index( Request $request )
  {
    $empresas = Empresa::formatList( $request->input('empresa_id', null) );

    return view('admin.usuarios_documentos.mantenimiento', compact('empresas'));
  }

  public function create( $id_empresa = "all" , $id_user = "all")
  {
    $tipo_documentos = TipoDocumentoPago::validDocumentos();
    $id_empresa = $id_empresa == 'all' ? "001"  : $id_empresa;
    $empresa = Empresa::find($id_empresa);
    empresa_bd_tenant( $id_empresa);
    $users = $id_user == "all" ? $empresa->users : User::findOrfail($id_user);
    $locales = Local::with(['empresa'])->get();
    // $plantillas_group = PDFPlantilla::where( 'tipo' , PDFPlantilla::TIPO_VENTA)->get()->groupBy('formato');
    $plantillas_group = PDFPlantilla::get()->groupBy([ 'tipo', 'formato']);

    $usuario_documento = new SerieDocumento();
    return view('admin.usuarios_documentos.create',
      compact( 'empresa', 'users','locales', 'usuario_documento', 'tipo_documentos','id_empresa','id_user', 'plantillas_group')
      );            
  }

  public function store( UsuarioAsignarDocumentoStoreRequest $request )
  {
    $data = [];
    $data["empcodi"] = $request->empresa_id;
    $data["usucodi"] = $request->usucodi;
    $data["tidcodi"] = $request->tidcodi;
    $data["loccodi"] = $request->loccodi;
    $data['numcodi'] = agregar_ceros( $request->input('numcodi') , 6 , 0);
    $data['defecto'] = (int) $request->input('defecto', 0);
    $data['estado' ] = (int) $request->input('estado', 0);
    $data['sercodi' ] = strtoupper($request->sercodi);
    $data['contingencia'] = (int) $request->input('contingencia',0);
    $data['a4_plantilla_id'] = $request->input('a4_plantilla_id');
    $data['a5_plantilla_id'] = $request->input('a5_plantilla_id', null);
    $data['ticket_plantilla_id'] = $request->input('ticket_plantilla_id', null);
    $data['impresion_directa'] = $request->input('impresion_directa');
    $data['cantidad_copias'] = $request->input('cantidad_copias');
    $data['nombre_impresora'] = $request->input('nombre_impresora');
    
    SerieDocumento::create($data);

    session()->put('usucodi', $request->usucodi);

    noti()->success('Documento asignado correctamente' , 'Se ha registrado correctamente');
    return redirect()->route('admin.usuarios_documentos.mantenimiento');
  }

  public function edit($id)
  {
    $usuario_documento = SerieDocumento::findOrfail($id);
    $empresa = $usuario_documento->empresa;
    empresa_bd_tenant($empresa->id());
    $tipo_documentos = TipoDocumentoPago::validDocumentos();
    $users = $usuario_documento->user;
    $locales = Local::all();
    $tipo = $usuario_documento->getTipo();
    $plantillas_group = PDFPlantilla::get()->groupBy(['tipo', 'formato']);
    $id_user = null;
    return view('admin.usuarios_documentos.edit', compact('tipo_documentos', 'empresa', 'id_user' , 'usuario_documento', 'users', 'locales', 'plantillas_group')); 
  }

  public function show($id)
  {

  }

  public function update(Request $request, $id)
  {
    $data = [];
    $data["tidcodi"] = $request->tidcodi;
    $data["loccodi"] = $request->loccodi;
    $data['numcodi'] = agregar_ceros($request->input('numcodi'), 6, 0);
    $data['defecto'] = (int) $request->input('defecto', 0);
    $data['estado'] = (int) $request->input('estado', 0);
    $data['sercodi'] = strtoupper($request->sercodi);
    $data['contingencia'] = (int) $request->input('contingencia', 0);
    $data['a4_plantilla_id'] = $request->input('a4_plantilla_id');
    $data['a5_plantilla_id'] = $request->input('a5_plantilla_id',null);
    $data['ticket_plantilla_id'] = $request->input('ticket_plantilla_id',null);
    $data['impresion_directa'] = $request->input('impresion_directa');
    $data['cantidad_copias'] = $request->input('cantidad_copias');
    $data['nombre_impresora'] = $request->input('nombre_impresora');

    $usuario_documento = SerieDocumento::findOrfail($id);
    $usuario_documento->update($data);
    session()->put('usucodi', $usuario_documento->usucodi);
    noti()->success('Acción exitosa' , 'Serie modificada exitosamente');
    return redirect()->route('admin.usuarios_documentos.mantenimiento');
  }

  public function delete( Request $request , $id)
  {
    $serie_documento = SerieDocumento::findOrfail($id);
    $serie_documento->delete();
    notificacion('Documento eliminado' , 'Se ha eliminado satisfactoriamente');
    return redirect()->route('admin.usuarios_documentos.mantenimiento');    
  }

  public function testPrintDirect( Request $request )
  {
    $empresa = get_empresa();
    $printName =  trim($request->input('impresora_nombre'));
    $data = [
      'empresa_logo_path' => $empresa->getUrlLogoTicket(), 
      'empresa_nombre' => $empresa->nombre(), 
      'empresa_direccion' => $empresa->direccion(), 
      'empresa_ruc' => $empresa->ruc(), 
      'empresa_telefonos' => $empresa->telefonos(), 
      'empresa_correos' => $empresa->email(), 
      'impresora_nombre' => $printName,
    ];
    return response()->json([ 'message' => 'Exitosamente', 'data' => $data ] , 200 );
  }
}