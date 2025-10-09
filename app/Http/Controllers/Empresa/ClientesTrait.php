<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use Illuminate\Http\Request;

trait ClientesTrait
{
  public function updateClientesInfo(Request $request, $id)
  {
    ini_set('max_execution_time', 600);
    ini_set('memory_limit', '450M');

    $empresa = Empresa::find($id);

    $request->validate([
      'tipo_documento' => 'required|in:6,6-20,1,all',
      'tipo_informacion' => 'required|in:retencion,informacion,all',
    ]);


    try {
      $empresa->updateClientesInfo($request->tipo_documento, $request->tipo_informacion);
    } catch (\Exception $e) {
      noti()->error('Acción Fallida', 'Error al actualizar la información de los clientes: ' . $e->getMessage() );
      return redirect()->back();
    }

    noti()->success('Acción Exitosa', 'Se han guardado exitosamente la información de los clientes');
    return redirect()->back();
  }
}
