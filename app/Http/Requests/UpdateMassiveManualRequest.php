<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMassiveManualRequest extends FormRequest
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
          'data.*.UniPUCD' => 'required|numeric|min:0',
          'data.*.UniPUCS' => 'required|numeric|min:0',
          'data.*.UniMarg' => 'required|numeric|min:0',
          'data.*.UNIPUVS' => 'required|numeric|gte:data.*.UniPUCS',
          'data.*.UNIPUVD' => 'required|numeric|gte:data.*.UniPUCD',
          'data.*.UNIPMVS' => 'required|numeric|min:0',
          'data.*.UNIPMVD' => 'required|numeric|min:0',          
        ];
    }

  public function messages()
  {
    return [
      'data.*.UNIPUVS.gt' => 'El precio de venta en soles, no puede ser menor al costo en soles ',
      'data.*.UNIPUVD.gt' => 'El precio de venta en dolares, no puede ser menor al costo en dolares ',
    ];
  }    
}
