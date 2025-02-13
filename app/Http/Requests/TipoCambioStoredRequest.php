<?php

namespace App\Http\Requests;

use App\TipoCambioPrincipal;
use Illuminate\Foundation\Http\FormRequest;

class TipoCambioStoredRequest extends FormRequest
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
            'fecha' => 'required|date|before_or_equal:' . date('Y-m-d'),
            'compra' => 'numeric|min:0.1',
            'venta' => 'numeric|min:0.1',
        ];
    }

    public function withValidator($validator)
    {
      if(!$validator->fails()){
        $validator->after(function($validator){
          
          
          if( date('Y-m-d') != $this->fecha ){
            $tc = TipoCambioPrincipal::where('TipFech', $this->input('fecha'))->first();
          
            if($tc){
              $validator->errors()->add('fecha', 'La fecha de tipo de cambio ya existe');
            }
          }

        });
      }
    }
}
