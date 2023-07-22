<?php

namespace App\Presenter;

class EmpresaPresenter extends Presenter
{
  public function getDocumentosReporte()
  {
    $class_name = "btn-default";
    $enlace = route('admin.empresas.reporte_documentos', ['id' => $this->model->id()]);
    $name = "Documentos";
    return sprintf('<a target="_blank class="btn btn-xs %s" href="%s">%s</a>', $class_name, $enlace, $name);
  }


  public function getAmbiente()
  {
    $isProduccion = $this->model->produccion();
    $class_name =  $isProduccion ? "btn-success" : 'btn-default';
    $enlace = $isProduccion ? "#" : '#';
    $name = $isProduccion ? "Producción" : 'Desarrollo';
    return sprintf('<a class="btn btn-xs %s" href="%s">%s</a>', $class_name, $enlace, $name );
  }
  
  public function getEnlaceSuscripcion()
  {
    $suscripcionVencida = $this->model->isSuscripcionVencida();
    $estatus = $suscripcionVencida ? "0" : "1";
    $suscripcion_actual = $this->model->suscripcionActual();

    $enlace = $suscripcion_actual ? route('admin.suscripcion.show', optional($suscripcion_actual)->id) : '#';
    $estatus_name = $suscripcionVencida ? "Vencida" : "Activa";

    $name =  sprintf(
      '<span class="plan-codigo">%s</span> <span class="plan-fecha">%s</span> <span class="plan-estatus">%s</span>', 
      optional(optional(optional($suscripcion_actual)->orden)->planduracion)->codigo,
      $this->model->end_plan,
      $estatus_name
    );
    
    return sprintf('<a class="btn-suscripcion-enlace suscripcion-%s" href="%s">%s</a>', $estatus, $enlace, $name );
  }
  
}