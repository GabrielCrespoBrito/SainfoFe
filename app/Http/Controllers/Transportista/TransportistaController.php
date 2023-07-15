<?php

namespace App\Http\Controllers\Transportista;

use App\Transportista;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transportista\TransportistaRequest;
use App\TipoDocumento;

class TransportistaController extends Controller
{
	public $model;

	public function __construct()
	{
		$this->model = new Transportista();
    $this->middleware( p_midd('A_INDEX', 'R_TRANSPORTISTA') )->only('index');
    $this->middleware( p_midd('A_CREATE', 'R_TRANSPORTISTA') )->only('create');
    $this->middleware( p_midd('A_EDIT', 'R_TRANSPORTISTA') )->only('edit');
    $this->middleware( p_midd('A_DELETE', 'R_TRANSPORTISTA') )->only('destroy');    
	}

	/**
	 * Busqueda por ajax
	 *
	 * @param Request $request
	 * @return void
	 */
	public function search(Request $request)
	{
		$term = $request->data;

		if (empty($term)) {
			$models = $this->model->get();
		}

		else {
			$models = $this->model->descripcion($term)->get();
		}

		$data = [];

		foreach ($models as $model) {
			$text = $model->descripcionComplete();
			$data[] = [ 'id' => $model->id, 'text' => $text ];
		}

		return \Response::json($data);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$transportistas = $this->model->get();
		return view('transportista.index', [
			'transportistas' => $transportistas
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
    $tiposdocumento = TipoDocumento::TRANSPORTISTAS;   
		return view('transportista.create', [
			'model' => $this->model,
      'tiposdocumento' => $tiposdocumento
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(TransportistaRequest $request)
	{
		$this->model->create($request->only('Nombres', 'Apellidos', 'TDocCodi', 'TraRucc', 'TraDire', 'TraTele', 'TraLice'));
		notificacion('Accion exitosa', 'Se ha creado satisfactoriamente el recurso');
		return redirect()->route('transportista.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
    $tiposdocumento = TipoDocumento::TRANSPORTISTAS;
		$model = $this->model->findOrfail($id);
		return view('transportista.edit', [
			'model' => $model,
      'tiposdocumento' => $tiposdocumento

		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(TransportistaRequest $request, $id)
	{
		$model = $this->model->findOrfail($id);
		$model->update($request->only('Nombres', 'Apellidos', 'TDocCodi', 'TraRucc', 'TraDire', 'TraTele', 'TraLice'));
		notificacion('Accion exitosa', 'Se ha actualizado satisfactoriamente el recurso');
		return redirect()->route('transportista.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
    $model = Transportista::findOrfail($id);

    if($model->guias->count() ){
      noti()->error('Accion cancelada', 'No se puede eliminar el tranportista porque tiene guias asociadas');
      return redirect()->route('transportista.index');
    }

		$model->delete();
		notificacion('Accion exitosa', 'Se ha eliminado satisfactoriamente el recurso');
		return redirect()->route('transportista.index');
	}
}
