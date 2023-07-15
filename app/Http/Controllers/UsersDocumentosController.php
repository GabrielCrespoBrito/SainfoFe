<?php

namespace App\Http\Controllers;
use App\User;
use App\Local;
use App\PDFPlantilla;
use App\SerieDocumento;
use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helper\PDFDirect\PDFPrintTest;
use App\Http\Requests\UsuarioAsignarDocumentoStoreRequest;
use Svg\Tag\Rect;

class UsersDocumentosController extends Controller
{

  function __construct()
  {
    $this->middleware('isAdmin');
  }

  public function search()
  {
    $busqueda = SerieDocumento::with(['user','local','empresa'])
    ->where('empcodi' , get_empresa('empcodi') )
    ->get();
    return DataTables::of($busqueda)
    ->addColumn('acciones', 'usuarios_documentos.partials.column_accion')
    ->rawColumns([ 'acciones' ])    
    ->make(true);
  }

  public function index()
  {
    return view('usuarios_documentos.mantenimiento');
  }

  public function create( $id_empresa = "all" , $id_user = "all")
  {    
    $tipo_documentos = TipoDocumentoPago::validDocumentos();
    
    $users = $id_user == "all" ? get_empresa()->users : User::findOrfail($id_user);
    $locales = Local::with(['empresa'])->get();
    $plantillas_group = PDFPlantilla::where( 'tipo' , PDFPlantilla::TIPO_VENTA)->get()->groupBy('formato');
    $usuario_documento = new SerieDocumento();

    return view('usuarios_documentos.create',
      compact('users','locales', 'usuario_documento', 'tipo_documentos','id_empresa','id_user', 'plantillas_group')
      );            
  }

  public function store( UsuarioAsignarDocumentoStoreRequest $request )
  {
    $data_arr = $request->except('_token');

    $local = Local::find($data_arr["loccodi"] );
    $data = [];
    $data["empcodi"] = $local->EmpCodi;
    $data["usucodi"] = $request->usucodi;
    $data["tidcodi"] = $request->tidcodi;
    $data["loccodi"] = $request->loccodi;
    $data['numcodi'] = agregar_ceros( $request->input('numcodi') , 6 , 0);
    $data['defecto'] = (int) $request->input('defecto', 0);
    $data['estado' ] = (int) $request->input('estado', 0);
    $data['sercodi' ] = strtoupper($request->sercodi);
    $data['contingencia'] = (int) $request->input('contingencia',0);
    $data['a4_plantilla_id'] = $request->input('formato_a4');
    $data['a5_plantilla_id'] = $request->input('formato_a5',);
    $data['ticket_plantilla_id'] = $request->input('formato_ticket',);
    $data['impresion_directa'] = $request->input('impresion_directa');
    $data['cantidad_copias'] = $request->input('cantidad_copias');
    $data['nombre_impresora'] = $request->input('nombre_impresora');

    SerieDocumento::create($data);

    notificacion('Documento asignado correctamente' , 'Se ha registrado correctamente');
    return redirect()->route('usuarios_documentos.mantenimiento');
  }

  public function edit($id)
  {
    $tipo_documentos = TipoDocumentoPago::validDocumentos();
    $usuario_documento = SerieDocumento::findOrfail($id);
    $users = $usuario_documento->user;

    $locales = Local::all();
    $plantillas_group = PDFPlantilla::where('tipo', PDFPlantilla::TIPO_VENTA)->get()->groupBy('formato');
    $id_user = null;    
    return view('usuarios_documentos.edit',compact('tipo_documentos', 'id_user' , 'usuario_documento', 'users', 'locales', 'plantillas_group')); 
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
    $data['a4_plantilla_id'] = $request->input('formato_a4');
    $data['a5_plantilla_id'] = $request->input('formato_a5',);
    $data['ticket_plantilla_id'] = $request->input('formato_ticket',);
    $data['impresion_directa'] = $request->input('impresion_directa');
    $data['cantidad_copias'] = $request->input('cantidad_copias');
    $data['nombre_impresora'] = $request->input('nombre_impresora');

    $usuario_documento = SerieDocumento::findOrfail($id);
    $usuario_documento->update($data);
    notificacion('Documento modificado' , '');
    return redirect()->route('usuarios_documentos.mantenimiento');
  }

  public function delete( Request $request , $id)
  {
    $serie_documento = SerieDocumento::findOrfail($id);
    $serie_documento->delete();
    notificacion('Documento eliminado' , 'Se ha eliminado satisfactoriamente');
    return redirect()->route('usuarios_documentos.mantenimiento');    
  }

  public function testPrintDirect( Request $request )
  {
    // PDFPrintTest
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