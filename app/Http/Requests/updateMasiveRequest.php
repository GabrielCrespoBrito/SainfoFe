<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateMasiveRequest extends FormRequest
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
          'tipo' => 'required|in:0,1',
          'campo' => 'required|in:costo,margen,precios_min',
          'value' => 'required|numeric|min:1|max:100',
          'lista_id' => 'nullable',
          'grupo_id' => 'required',
          'familia_id' => 'nullable',
          'marca_id' => 'nullable',
          'local_id' => 'required',
        ];
    }

    // @TODO Validar que el grupo sea correcto, que la familia si existe, pertenezaca al grupo, etc, etc
    public function withValidator($validator)
    {
      if(!$validator->fails()){
        $validator->after(function($validator){
           // $validator->errors()->add('field','message');
           // $this->route()->parameters['field'] 
        });
      }
    }
}


