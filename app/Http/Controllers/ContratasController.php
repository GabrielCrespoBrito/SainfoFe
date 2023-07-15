<?php

namespace App\Http\Controllers;

use App\Contrata;
use Illuminate\Http\Request;

class ContratasController extends Controller
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
		$contratas = Contrata::all();
		// dd($contratas);
		return view('contratas.index', compact('contratas'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('contratas.create');
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
			'nombre' => 'required|unique:contratas,nombre',
			'contenido' => 'required',            
		]);

		if ($validator->fails())  {
			return \Response::json(array("errors" => $validator->getMessageBag()->toArray()), 422);
		}

		Contrata::create( $request->only("nombre", "contenido") );

		return response()->json(['message' => 'Se ha registrado correctamente el documento' , 'url' => route('contratas.index') ]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$contrata = Contrata::findOrfail($id);

		return view( 'contratas.show' , compact('contrata'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$contrata = Contrata::findOrfail($id);

		return view( 'contratas.edit' , compact('contrata'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$contrata = Contrata::find($id);

		$validator = \Validator::make( $request->all(), [
			'nombre' => "required|unique:contratas,nombre,{$id}",
			'contenido' => 'required',
		]);

		if ($validator->fails())  {
			return \Response::json(array("errors" => $validator->getMessageBag()->toArray()), 422);
		}

		$contrata->update( $request->only("nombre", "contenido") );

		return response()->json(['message' => 'Actualización realizada exitosamente' , 'url' => route('contratas.index') ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$contrata = Contrata::findOrfail($id);		
		$nombre = $contrata->nombre;
		if( $contrata->documentos->count() ){
			foreach ( $contrata->documentos as $documento ) {
				$documento->update(["contrata_id" => null ]);
			}			
		}
		$contrata->delete();		
		notificacion("Se ha eliminado exitosamente el documento {$nombre}", null , "success" );
		return redirect()->route('contratas.index');


	}

	public function pdf( $id ){

		$contrata = Contrata::findOrfail($id);
		$name = 'contratas.pdf';
		return streamPdf( $name , ['contenido'=> $contrata->contenido, 'title' => $contrata->nombre ] , $name );
	}

}
