<?php

namespace App\Http\Requests;

use App\Compra;
use Illuminate\Foundation\Http\FormRequest;

class GuiaIngresoFromCompraRequest extends FormRequest
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
          'serie' => 'required|min:1|max:4',
          'numero' => 'required|digits_between:1,8',
        ];
    }

    public function withValidator($validator)
    {
      if(!$validator->fails()){
        $validator->after(function($validator){
          $compra = Compra::find( $this->route()->parameters()['id'] );
          if ($compra->despachoCompletado()) {
            // $validator->errors()->add('field','La Compra ya tiene su guia de ingreso');
            // return;
          }
        });
      }
    }
}
