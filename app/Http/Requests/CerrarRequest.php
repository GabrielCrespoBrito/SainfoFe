<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Caja;

class CerrarRequest extends FormRequest
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

      $validator->after(function ($validator){

        $caja = Caja::find( $this->id_caja );

        if( is_null($caja) ){
          $validator->errors()->add('caja_id',  'Esta caja no existe en esta empresa');
        }

        if( !$caja->isAperturada() ){
          $validator->errors()->add('caja_id',  'Esta caja ya se encuentra esta cerrada');          
        }


      });

    }
  }

}
