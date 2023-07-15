<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Marca;

class MarcaStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
		return  [
			'MarCodi' => 'required|numeric|digits_between:1,4',
			'MarNomb' => 'required|max:50',			
		];
    }

	public function withValidator($validator)
	{
		if( !$validator->fails() ){

			$validator->after(function ($validator){
				
				if(Marca::where('MarCodi', $this->MarCodi)->count()){
					$validator->errors()->add('MarCodi', 'El codigo (' . $this->MarCodi . ') esta repetido' );
				}

				if(Marca::where('MarNomb', $this->MarNomb)->count()){
					$validator->errors()->add('MarNomb', 'El nombre (' .  $this->MarNomb .  ') esta repetido' );
				}

			});

		}
		

	}

	public function messages(){
		return [
			'MarCodi.numeric' => 'El codigo tiene que ser un numero',
			'MarCodi.max' => 'El codigo solo puede tener 4 cifras',
			'MarNomb.required' => 'El nombre de la marca es obligatorio',
			'MarNomb.max' => 'El nombre de la marca no puede tener mas de 50 caracteres',
		];
	}
}


//