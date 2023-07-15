<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Caja;

class BorrarCajaRequest extends FormRequest
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
    $validator->after(function ($validator) {

      $caja = Caja::find($this->id_caja);

      if( $caja->tiene_movimientos() ){
        $validator->errors()->add('tipo', 'Esta caja tiene movimiento no se puede eliminar'); 
      }

    });
  }

}
