<?php

namespace App\Http\Controllers\Admin\Landing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Admin\Models\Cliente\Cliente;
use App\Http\Requests\Landing\ClienteRequest;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
  public function index()
  {
    return view('admin.landing.clientes.index');
  }

  public function search()
  {
    return response()->json([
      'clients' => Cliente::allWithLogoPath()
    ]);
  }

  public function store( ClienteRequest $request )
  {
    DB::beginTransaction();
    try {
      $cliente = new Cliente;
      $cliente->razon_social = $request->razon_social;
      $cliente->ruc = $request->ruc;
      $cliente->sitio = $request->sitio;
      $cliente->active = $request->active;
      $cliente->save();
      $cliente->logo_path = $cliente->uploadImage($request->file('image'), $cliente->getImageName());
      $cliente->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json([
        'message' => $th->getMessage()
      ], 400);
    }

    return [
      'message' => 'Cliente Guardado Exitosamente'
    ];

  }

  public function update(ClienteRequest $request, $id)
  {
    DB::beginTransaction();
    try {
      $cliente = Cliente::find($id);
      $cliente->razon_social = $request->razon_social;
      $cliente->ruc = $request->ruc;
      $cliente->sitio = $request->sitio;
      $cliente->active = $request->active;
      $file = $request->file('image');            
      if( $file ){
        $cliente->deleteImage();
        $cliente->logo_path = $cliente->uploadImage($file, $cliente->getImageName());
      }

      $cliente->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
    }

    return [
      'message' => 'Cliente Guardado Exitosamente'
    ];
  }


  public function delete( $clienteId )
  {
    $cliente = Cliente::find($clienteId);
    $cliente->deleteImage($this->logo_path);
    $cliente->delete();    
    return response()->json([
      'message' => 'Eliminacion Exitosa'
    ]);
  }

}
