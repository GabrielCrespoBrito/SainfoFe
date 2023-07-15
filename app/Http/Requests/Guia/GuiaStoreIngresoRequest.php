<?php

namespace App\Http\Requests\Guia;

use App\Compra;
use Illuminate\Foundation\Http\FormRequest;

class GuiaStoreIngresoRequest extends FormRequest
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
      'serie'    => 'required|alpha_num|max:4',
      'numero'   => 'required|digits_between:1,12',
      'almacen'  => 'required'
    ];
  }

  public function withValidator($validator)
  {
    if( !$validator->fails() ){

      $validator->after(function ($validator) {

        $id = $this->route()->parameters['id'];

        $compra = Compra::find($id);

        if ( $compra->guia ) {
          $validator->errors()->add('compra', 'Esta compra ya tiene una guia asociada');
          return;
        }

        if (!get_empresa()->hasLocal($this->almacen)) {
          $validator->errors()->add('compra', 'El local no existe');
          return;
        }

      });
    }
  }
}
