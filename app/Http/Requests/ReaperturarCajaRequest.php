<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Caja;

class ReaperturarCajaRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'id_caja' => 'required'
    ];
  }

  public function withValidator($validator)
  {
    if( !$validator->fails() ){

      $validator->after(function ($validator) {

        $empresa = get_empresa();  

        $caja = Caja::find($this->id_caja);         
        $empresa_cajas = $empresa->cajas;

        if( is_null($caja) ){
          $validator->errors()->add('caja', 'Esta empresa no tiene una caja con este codigo');
          return;
        }

        $lastCajaNume =
        $empresa_cajas
        ->where('LocCodi', $caja->LocCodi )
        ->where('UsuCodi', $caja->UsuCodi )        
        ->max('CajNume');


        // Gate::allows('update-post', $post)
        // if( $caja->UsuCodi != usucodi() ){
        //   $validator->errors()->add('tipo', 'No puede manipular la caja de otro usuario');
        // }

        if( $lastCajaNume != $caja->CajNume ){
          $validator->errors()->add('tipo', 'Solo se puede reaperturar la ultima caja'); 
        }

        elseif( $caja->isAperturada() ){
          $validator->errors()->add('tipo', 'Esta caja ya esta aperturada'); 
        }

      });

    }
  }

}
