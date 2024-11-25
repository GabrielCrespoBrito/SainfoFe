<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendedorDestroyRequest;
use App\Vendedor;
use Illuminate\Http\Request;
use App\Http\Requests\VendedorRequest;

class VendedorController extends Controller
{
  public $model;
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
	public function index(  Request $request )
	{
		$delete = $request->input( 'delete', 0 );
    $route = $delete ? route('vendedor.index') : route('vendedor.index', ['delete' => 1]);
		$vendedores = $delete ? $this->model->withoutGlobalScope('noEliminados')->where('UDelete', '=', '*')->get() : $this->model->get();
    // dd($vendedores);
		return view('vendedores.index', compact('vendedores', 'delete', 'route'));
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
	public function destroy( $id)
	{
    $vendedor =  $this->model->withoutGlobalScope('noEliminados')->findOrfail($id);

    $result = $vendedor->isInUse();

    if($result){
      $vendedor->UDelete = "*";
      $vendedor->save();
      $message = 'El vendedor ha sido ocultado exitosamente';

    }
    else {
      $message = "El vendedor ha sido eliminado exitosamente";
      $vendedor->delete();
    }

    notificacion('Acción exitosa', $message );
    return $result ? redirect()->route('vendedor.index', ['delete' => 1]) : redirect()->route('vendedor.index');
	}


  //


  public function restaurar($id)
  {
    // $this->authorize(p_name('A_DELETE', 'R_PRODUCTO'));
    $vendedor =  $this->model->withoutGlobalScope('noEliminados')->find($id);
    // dd($vendedor);
    $vendedor->UDelete = 0;
    $vendedor->save();
    return redirect()->route('vendedor.index', ['delete' => 1]);
  }

}
