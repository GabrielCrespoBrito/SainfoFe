<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuscarClienteRequest extends FormRequest
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
            'codigo' => 'required|exists:prov_clientes,PCRucc',            
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'El numero del documento es necesario',
            'codigo.exists'  => 'Este numero de documento no existe',
        ];
    }
}
