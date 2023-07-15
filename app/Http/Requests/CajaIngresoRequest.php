<?php

namespace App\Http\Requests;

use App\Caja;
use Illuminate\Foundation\Http\FormRequest;

class CajaIngresoRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'moneda' => 'required|exists:moneda,moncodi',
      'fecha' => 'required|date',
      'motivo' => 'required|max:100',
      'nombre' => 'required|max:100',
      'monto' => 'required|numeric|min:0',
      'autoriza' => 'required|max:65',
    ];
  }

  public function withValidator($validator){

// if( !$validator->fails()){
  
  // $validator->after(function($validator) use($caja_current) {
    
    // if( !$caja_current->isAperturada() ){
    //   $validator->errors()->add('caja', 'La caja con la que esta trabajando no esta aperturada');
    // }
//

    if( !$validator->fails() ){
      $validator->after(function($validator){
        $caja_current = Caja::find($this->route()->parameters["caja_id"]);
        if( !$caja_current->isAperturada() ){
          $validator->errors()->add('caja', 'La caja con la que esta trabajando no esta aperturada');
        }

      });
    }
  }
}
