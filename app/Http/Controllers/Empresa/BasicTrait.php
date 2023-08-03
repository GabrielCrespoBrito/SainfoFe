<?php
namespace App\Http\Controllers\Empresa;

use App\Empresa;
use App\Http\Requests\Empresa\UpdateBasicRequest;

trait BasicTrait 
{
  public function updateDataBasic(UpdateBasicRequest $request, $id)
  {
    $empresa = Empresa::find($id);
    $data = $request->only('nombre_comercial', 'direccion', 'ubigeo', 'departamento', 'provincia', 'distrito', 'email', 'telefonos', 'rubro', 'active', 'emis_certificado', 'venc_certificado');
    $empresa->EmpLin2 = $data['direccion'];
    $empresa->EmpLin3 = $data['email'];
    $empresa->EmpLin4 = $data['telefonos'];
    $empresa->EmpLin5 = $data['nombre_comercial'];
    $empresa->EmpLin6 = $data['rubro'];
    $empresa->setUbigeo($data['ubigeo']);
    if( isset($data['venc_certificado'])  ){
      $empresa->venc_certificado = $data['venc_certificado'];
    }
    if (isset($data['emis_certificado'])) {
      $empresa->emis_certificado = $data['emis_certificado'];
    }
    $empresa->save();
    $empresa->cleanCache();
    noti()->success('Acción exitosa', 'Se ha modificado exitosamente la información de la empresa');
    return back();
  }
}