<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadUpdateRequest extends FormRequest
{

  const RULES = [
      'UniPUCD' => 'required|numeric|min:0',
      'UniPUCS' => 'required|numeric|min:0',
      'UniMarg' => 'required|numeric|min:0',
      'UNIPUVS' => 'required|numeric|gte:UniPUCS',
      'UNIPUVD' => 'required|numeric|gte:UniPUCD',
      'UNIPMVS' => 'required|numeric|min:0',
      'UNIPMVD' => 'required|numeric|min:0',   
      'porc_com_vend' => 'required|numeric|min:0|max:100',   
  ];


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
    return self::RULES;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        // $validator->errors()->add('field','message');
        // $this->route()->parameters['field'] 
      });
    }
  }

  public function messages()
  {
    return [
      'UNIPUVS.gt' => 'El precio de venta en soles, no puede ser menor al costo en soles ',
      'UNIPUVD.gt' => 'El precio de venta en dolares, no puede ser menor al costo en dolares ',
    ];
  }

}
