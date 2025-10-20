<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use App\Http\Requests\Empresa\UpdateBasicRequest;

trait UsosTrait
{
  public function updateUsos($empresa_id)
  {
    $empresa = Empresa::find($empresa_id);
    $empresa->updateUsos();
    notificacion('Accion exitosa', 'Se ha actualizado los valores de la suscripción de la empresa');
    return back();
  }

  public function updatePreciosProductos()
  {
    abort_if(!auth()->user()->isAdmin(), 300);
    ini_set('max_execution_time', 240);
    get_empresa()->updatePreciosProductos();
    notificacion('Actualización exitosa', 'La Actualización de precios ha sido ejecutada satisfactoriamente');
    return redirect()->route('home');
  }

  public function updateValorVenta()
  {
    abort_if(!auth()->user()->isAdmin(), 300);
    ini_set('max_execution_time', 240);
    get_empresa()->updateValorVenta();
    notificacion('Actualización exitosa', 'La Actualización de precios ha sido ejecutada satisfactoriamente');
    return redirect()->route('home');
  }

  public function updateCostosReales($id = null)
  {
    abort_if(!auth()->user()->isAdmin(), 300);
    ini_set('max_execution_time', 600);
    get_empresa()->updateCostosReales();
    notificacion('Actualización exitosa', 'La Actualización de precios ha sido ejecutada satisfactoriamente');
    return redirect()->route('home');
  }

}
