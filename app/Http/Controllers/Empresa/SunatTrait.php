<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use Illuminate\Http\Request;

trait SunatTrait
{
  public function updateSunat(Request $request, $id)
  {
    $empresa = Empresa::find($id);
    $data = $request->only('cert_nomb', 'clave_firma', 'usuario_sol', 'clave_sol', 'fe_envfact', 'fe_envncre', 'fe_envbole', 'fe_servicio', 'fe_ambiente', 'url_consulta', 'FE_CLIENT_ID', 'FE_CLIENT_KEY');
    $empresa->FE_CERT = $data['cert_nomb'];
    $empresa->FE_CLAVE = $data['clave_firma'];
    $empresa->FE_USUNAT   = $data['usuario_sol'];
    $empresa->FE_UCLAVE   = $data['clave_sol'];
    $empresa->FE_CLIENT_ID   = $data['FE_CLIENT_ID'];
    $empresa->FE_CLIENT_KEY   = $data['FE_CLIENT_KEY'];
    $empresa->fe_envfact  = (int) isset($data['fe_envfact']);
    $empresa->fe_envbole  = (int) isset($data['fe_envbole']);
    $empresa->fe_envncre  = (int) isset($data['fe_envncre']);
    $empresa->fe_envndebi = (int) isset($data['fe_envndebi']);
    $empresa->fe_servicio = $data['fe_servicio'];
    $empresa->fe_ambiente = $data['fe_ambiente'];
    $empresa->fe_consulta = $data['url_consulta'];    
    $empresa->save();
    $empresa->cleanCache();
    noti()->success('Acción exitosa', 'Se ha modificado exitosamente la información de la empresa');
    return back();
  }
}
