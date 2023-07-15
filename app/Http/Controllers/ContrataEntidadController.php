<?php

namespace App\Http\Controllers;

use App\ClienteProveedor;
use App\Contrata;
use App\ContrataEntidad;
use App\Helpers\MailH;
use Illuminate\Http\Request;

class ContrataEntidadController extends Controller
{
	public function __construct(){
		$this->middleware('isAdmin');
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$contratas = ContrataEntidad::all();
		return view('contratas_entidad.index', compact('contratas'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$documentos = Contrata::all();
		if( !$documentos->count() ){
			notificacion("Para crear un documento primero tiene que tener una plantilla", null , "error");
			return redirect()->route('contratas.index');
		}		
		$contrata_entidad = new ContrataEntidad();
		return view('contratas_entidad.create', compact('documentos','contrata_entidad'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validator = \Validator::make( $request->all(), [
			'entidad_tipo' => 'required',
			'entidad_id' => 'required',
			'fecha_emision' => 'required|date',			
			'fecha_inicio' => 'required|date',
			'fecha_final' => 'required|date',			
			'documento_id' => 'required|exists:contratas,id',            
		]);


		if ($validator->fails())  {
			return \Response::json(array("errors" => $validator->getMessageBag()->toArray()), 422);
		}

		// return $request->all();

		$data = [ 
			"entidad_type" => $request->entidad_tipo,
			"entidad_id"   => $request->entidad_id,
			"contrata_id"  => $request->documento_id,
			"fecha_emision"  => $request->fecha_emision,
			"fecha_inicio"  => $request->fecha_inicio,
			"fecha_final"  => $request->fecha_final,
			"contenido" 	 => "",
			'EmpCodi' 		 => empcodi()
		];

		$documento = Contrata::find( $request->documento_id );
		$contenido = $documento->contenido;

		$contrata_entidad = ContrataEntidad::create($data);
		$model = $contrata_entidad->getModel();
		$propsModel = $model->toArray();

		$propsModel['fecha_emision']= fixedDate($data['fecha_emision']);
		$propsModel['fecha_inicio'] = fixedDate($data['fecha_inicio']);
		$propsModel['fecha_final']  = fixedDate($data['fecha_final']);

		foreach( $propsModel as $prop => $value ){
			$prop = "[" . strtolower($prop) . "]";			
			$contenido = str_replace($prop, $value, $contenido);
		}

		$contrata_entidad->update([ 'contenido' => $contenido ]);

		return response()->json(['message' => 'Creación de documento creado exitosamente' , 'url' => route('contratas_entidad.edit' , $contrata_entidad->id ) ]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\ContrataEntidad  $contrataEntidad
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$contrataEntidad =  ContrataEntidad::findOrfail($id);
		$documentos = Contrata::all();				
		return view('contratas_entidad.show', ['contrata_entidad' => $contrataEntidad , 'documentos' => $documentos ]);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\ContrataEntidad  $contrataEntidad
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$contrataEntidad =  ContrataEntidad::findOrfail($id);
		$documentos = Contrata::all();		
		return view('contratas_entidad.edit', ['contrata_entidad' => $contrataEntidad , 'documentos' => $documentos ]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\ContrataEntidad  $contrataEntidad
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$contrataEntidad = ContrataEntidad::findOrfail($id);
		$contrataEntidad->update([ 'contenido' => $request->contenido ]);
		return response()->json(['message' => 'Se ha modificado el documento exitosamente' , 'url' => route('contratas_entidad.index') ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\ContrataEntidad  $contrataEntidad
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$contrataEntidad = ContrataEntidad::findOrfail($id);
 		$contrataEntidad->delete();		
		notificacion("Se ha eliminado exitosamente el documento", null , "success" );
		return redirect()->route('contratas_entidad.index');		
	}

	public function pdf($id)
	{
		$contrataEntidad = ContrataEntidad::find($id);
		$name = 'contratas.pdf';
		$contenido = $contrataEntidad->contenido;
		return streamPdf( $name , ['contenido' => $contenido , 'title' => $name ] , $name );
	}

	public function sendEmail( Request $request, $id)
	{
		$contrataEntidad = ContrataEntidad::find($id);
		MailH::contrata($id,$request->all());
		return response()->json(["message" => 'Envio exitoso']);


	}




}