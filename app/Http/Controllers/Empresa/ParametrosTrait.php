<?php

namespace App\Http\Controllers\Empresa;

use App\Venta;
use App\Empresa;
use App\EmpresaOpcion;
use App\SettingSystem;
use App\Http\Requests\EmpresaOptionsRequest;
use App\Http\Requests\EmpresaUpdateModuloRequest;


trait ParametrosTrait
{
  public function update_parametros(EmpresaOptionsRequest $request, $id)
  {
    $empresa = Empresa::find($id);
    $data = $request->only(EmpresaOpcion::FIELDS_MODIFY);
    $data['EmpCodi'] = $id;
    $opcion = $empresa->opcion;
    $igv = collect(json_decode(get_setting(SettingSystem::CAMPO_CONFIGURACION_IGV)))
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

    if ($request->fe_formato != $empresa->fe_formato) {
      $needUpdate = true;
      $dataUpdate['fe_formato'] = $request->fe_formato;
    }

    if ($request->tipo_caja != $empresa->{Empresa::CAMPO_TIPO_CAJA}) {
      $needUpdate = true;
      $dataUpdate[Empresa::CAMPO_TIPO_CAJA] = $request->tipo_caja;
    }

    if ($needUpdate) {
      $empresa->update($dataUpdate);
    }

    $empresa->cleanCache();

    noti()->success('Acción Exitosa', 'Se han guardado exitosamente las configuraciones de la empresa');
    return redirect()->back();
  }


  public function update_modulos(EmpresaUpdateModuloRequest $request, $id)
  {

    $empresa = Empresa::find($id);
    $data = $request->only(EmpresaOpcion::MODULOS);

    $empresa->updateModulos($data);
    $empresa->cleanCache();
    noti()->success('Acciòn exitosa', 'Se han guardado exitosamente las configuraciones de la empresa');
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


  public function updateValorVentaByDate($id = null, $fechaDesde, $fechaHasta)
  {
    abort_if(!auth()->user()->isAdmin(), 300);

    ini_set('max_execution_time', '600');
    ini_set('memory_limit', -1);

    $empresa = Empresa::find($id);
    empresa_bd_tenant($id);

    $docs = Venta::with('items')->whereBetween('VtaFvta', [$fechaDesde, $fechaHasta])->get();

    foreach ($docs as $doc) {


      $is_sol = $doc->isSol();
      $tcam = $doc->VtaTcam;


      // dd( $doc->items );


      foreach ($doc->items as $item) {

        if ($item->DetVSol == 0 || $item->DetVDol == 0) {

          $valor_unitario = $item->incluye_igv ? $item->DetPrec / (($item->DetIGVV / 100) + 1) : $item->DetPrec;
          $costo = $valor_unitario * $item->DetCant;
          $valor_soles = $is_sol ? $costo : $costo * $tcam;
          $valor_dolares = $is_sol ? $costo / $tcam : $costo;

          $item->DetVSol = $valor_soles;
          $item->DetVDol = $valor_dolares;

          // logger($doc->VtaOper . ' - ' . $doc->VtaNume, [$item->Linea,  $item->DetPrec,  $item->DetCant, $item->DetVSol, $item->DetVDol]);

          $item->save();
        }
      }
    }


    return "exito";
    return redirect()->back();
  }
}
