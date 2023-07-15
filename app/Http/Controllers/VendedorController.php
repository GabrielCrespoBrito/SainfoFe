<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendedorDestroyRequest;
use App\Vendedor;
use Illuminate\Http\Request;
use App\Http\Requests\VendedorRequest;

class VendedorController extends Controller
{
	public function __construct()
	{
		$this->model = new Vendedor();

    $this->middleware(p_midd('A_INDEX', 'R_VENDEDOR'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_VENDEDOR'))->only('create','store');
    $this->middleware(p_midd('A_EDIT', 'R_VENDEDOR'))->only('edit','update');
    $this->middleware(p_midd('A_DELETE', 'R_VENDEDOR'))->only('destroy');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$vendedores = $this->model->get();
		return view('vendedores.index', compact('vendedores'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('vendedores.create', [ 'model' => $this->model, 'create' => true, 'usuarios' => get_empresa()->users]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(VendedorRequest $request)
	{
    // _dd($request->only('vennomb', 'venmail', 'ventel1', 'vendire', 'usucodi'));
    // exit();

		$this->model->repository()->create($request->only('vennomb', 'venmail', 'ventel1', 'vendire', 'usucodi'));

		notificacion('Acción exitosa', 'La cuenta se ha creado exitosamente');
		return redirect()->route('vendedor.index');
	}



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$create = false;
		$model = Vendedor::findOrfail($id);
    $usuarios = get_empresa()->users;
		return view('vendedores.edit', compact('create', 'model', 'usuarios' ));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(VendedorRequest $request, $id)
	{
		$this->model->repository()->update($request->only('vennomb', 'venmail', 'ventel1', 'vendire', 'usucodi') , $id);
		notificacion('Acción exitosa', 'La vendedor se ha modificada exitosamente');
		return redirect()->route('vendedor.index');
	}

  
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( VendedorDestroyRequest $request, $id)
	{
		$this->model->repository()->delete($id);
		notificacion('Acción exitosa', 'El vendedor ha sido eliminado exitosamente');
		return redirect()->route('vendedor.index');
	}
}
