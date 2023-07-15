<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContrataEntidad extends Model
{
  protected $table = "contratas_entidad";

  protected $guarded = [];

  public function documento(){
    return $this->belongsTo( Contrata::class );
  }

  public function documentoName(){
    return is_null($this->documento) ? "-" : $this->documento->nombre;
  }


  public function contratable(){
    return $this->getModel();
  }

  public function getModel(){

    $model = $this->entidad_type;
    $id = $this->entidad_id;
    $empcodi = $this->EmpCodi;

    switch ($this->entidad_type) {
      case 'App\ClienteProveedor':      
        return $model::where('PCRucc' , $id )
        ->where('EmpCodi' , $empcodi )    
        ->first();
        break;
    }
  }

  public function nameEntidad(){

    $model = $this->getModel();
    
    switch ($this->entidad_type) {
      case 'App\ClienteProveedor':      
        return is_null($model)  ? "-" : $model->PCNomb;
        break;
    }
  }


  public function tipo(){

  	switch ($this->entidad_type) {
  		case 'App\ClienteProveedor':
  			return 'cliente';
  			break;

  		  case 'App\Empresa':
  			return 'empresa';
  			break;
  		default:
  			break;
  	}

  }


}
