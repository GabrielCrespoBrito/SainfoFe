<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use App\EmpresaOpcion;
use App\Http\Requests\EmpresaOptionsRequest;
use App\Http\Requests\EmpresaUpdateModuloRequest;
use App\SettingSystem;


trait ParametrosTrait
{
  public function update_parametros(EmpresaOptionsRequest $request, $id)
  {
    $empresa = Empresa::find($id);
    $data = $request->only( EmpresaOpcion::FIELDS_MODIFY );
    $data['EmpCodi'] = $id;
    $opcion = $empresa->opcion;
    $igv = collect(json_decode(get_setting( SettingSystem::CAMPO_CONFIGURACION_IGV )))
    ->where('codigo', $request->configuracion_igv)
    ->first()
    ->porc;
    
    $data['Logigv'] = $igv;
    $opcion->fill($data);
    
    if ($opcion->isDirty()) {
      $opcion->save();
    }

    $empresa->updateDataAdicional([
      'proforma_igv' => $request->proforma_igv,
      'configuracion_igv' => $request->configuracion_igv,
    ]);

    $needUpdate = false;
    $dataUpdate = [];

    if( $request->fe_formato != $empresa->fe_formato ) {
      $needUpdate = true;
      $dataUpdate['fe_formato'] = $request->fe_formato;
    }

    if ($request->tipo_caja != $empresa->{Empresa::CAMPO_TIPO_CAJA} ) {
      $needUpdate = true;
      $dataUpdate[Empresa::CAMPO_TIPO_CAJA] = $request->tipo_caja;
    }

    if($needUpdate){
      $empresa->update( $dataUpdate );
    }

    $empresa->cleanCache();

    noti()->success('Acciòn exitosa', 'Se han guardado exitosamente las configuraciones de la empresa');
    return redirect()->back();
  }


  public function update_modulos(EmpresaUpdateModuloRequest $request, $id)
  {
    $empresa = Empresa::find($id);
    $data = $request->only(EmpresaOpcion::MODULOS);
    $empresa->updateModulos($data);
    $empresa->cleanCache();
    noti()->success( 'Acciòn exitosa' , 'Se han guardado exitosamente las configuraciones de la empresa' );
    return redirect()->back();

  }



  public function updateCostosReales($id = null)
  {
    abort_if(!auth()->user()->isAdmin(), 300);

    ini_set('max_execution_time', '600');
    ini_set('memory_limit', -1); 
     
    $empresa = Empresa::find($id);
    empresa_bd_tenant($id);
    $empresa->updateCostosReales();
    notificacion('Actualización exitosa', 'La Actualización de costos ha sido ejecutada satisfactoriamente');
    return redirect()->back();
  }



  

}
