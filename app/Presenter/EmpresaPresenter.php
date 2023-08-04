<?php

namespace App\Presenter;

use Carbon\Carbon;

class EmpresaPresenter extends Presenter
{
  public function getDocumentosReporte()
  {
    $class_name = "btn-default";
    $enlace = route('admin.empresas.reporte_documentos', ['id' => $this->model->id()]);
    $name = "Documentos";
    return sprintf('<a target="_blank" class="btn btn-flat btn-xs %s" href="%s"> <span class="fa fa-file-text-o"></span> %s</a>', $class_name, $enlace, $name);
  }

  public function getColumnCert()
  {
    $fc = $this->model->emis_certificado . ' - ' . $this->model->venc_certificado; 
    $name  = "";    

    if( $fc == null ){
      $className = "btn-default";
      $name = "S/F";
    }

    else if( $this->model->fechaCertVencido() ){
      $className = "btn-danger";
      $name = "Vencido";
    }

    else if($this->model->fechaCertPorVencer() ){
      $className = "btn-warning";
      $name = "Por Vencer";
    }

    else {
      $className = "btn-success";
      $name = "Activo";
    }

    return sprintf('<a class="btn btn-flat btn-xs %s" href="#"> <span class="fa  fa-calendar"></span> %s <strong>%s</strong></a>', $className, $fc,  $name);
  }


  public function getTipoColumn()
  {
    if( $this->model->isWeb() ){
      $icon = "fa-cloud";
      $class_name = "btn-default";
      $name = "Web";
    }
    else {
      $icon = "fa-desktop";
      $class_name = "btn-primary";
      $name = "Escritorio";
    }
 

    return sprintf('<a class="no-pointer btn btn-flat btn-xs %s" href="#"> <span class="fa %s"></span> %s</a>', $class_name, $icon, $name);
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