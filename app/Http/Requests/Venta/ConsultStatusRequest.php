<?php

namespace App\Http\Requests\Venta;

use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class ConsultStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

public function withValidator($validator)
{
  if(!$validator->fails()){
    $validator->after(function($validator){

      $doc = Venta::findOrfail($this->route()->parameters['id'] );

      if( $doc->isBoleta() ){
        if( ! get_empresa()->envioBoleta() ){
          $validator->errors()->add('id', 'Las boletas enviadas por Resumen diario, no se puede consultar por este medio');
        }
      }
      else if( ! $doc->isValidForConsult() ){
        $validator->errors()->add('id', 'Solo se pueden consultar Facturas, Notas de credito y Notas de debido');
        return;
      }
    });
  }
}

}
