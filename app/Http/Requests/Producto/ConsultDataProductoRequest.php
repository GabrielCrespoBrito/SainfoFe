<?php

namespace App\Http\Requests\Producto;

use App\Producto;
use Illuminate\Foundation\Http\FormRequest;

class ConsultDataProductoRequest extends FormRequest
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
            'codigo' => 'required'
        ];
    }

    public function withValidator( $validator )
    {
      if( ! $validator->fails() ){
        // Extra validation
        $validator->after(function($validator){
          $campo = $this->input('campo', 'ProCodi');
          if(Producto::where($campo ,  $this->codigo)->first() == null ){
            $validator->errors()->add('codigo', 'No existe ningun producto con este codigo');
          }
        });
      }
    }
}