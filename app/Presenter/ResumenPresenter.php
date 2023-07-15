<?php 

namespace App\Presenter;

class ResumenPresenter extends Presenter
{
	public function userName()
	{
		return "this";
	}

  public function getClassSCssEstadoSunat()
  {
    if ($this->model->DocCEsta == "0") {
      return "bg-green";
    }
    else {
      return "bg-gray";
    }
  }

  public function getIconEstadoSunat()
  {
    if( $this->model->DocCEsta == "0" ){
      return "fa fa-check";
    }
    else {
      return "fa fa-spinner spinner spin";
    }
  }

  public function getTextEstadoSunat()
  {
    return $this->model->DocEstado;
  }

  public function getBtnColumnEstado()
  {
    return sprintf('<a href="#"> Aceptado Sunat </a>');
  }

}

?>