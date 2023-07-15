<?php

namespace App\Http\Requests;

use App\Familia;
use Illuminate\Foundation\Http\FormRequest;

class FamiliaUpdateRequest extends FormRequest
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

	public function rules()
	{
		return [
			'famCodi' => 'required',
			'gruCodi' => 'required',
			'famNomb' => 'required|max:200'

		];
	}

	public function messages(){
		return [            
			'gruCodi.exists' => 'El codigo del grupo no existe',            
			'famNomb.required' => 'El nombre de la familia es necesario',
			'famNomb.max' => 'El nombre no puede tener mas de 200 caracteres',			
		];
	}


	public function withValidator($validator)
	{

		if(!$validator->fails()) {

			$validator->after(function($validator) {

					$famlias = get_empresa()->familias;

					$familia_current = 
					$famlias
					->where('famCodi',$this->famCodi)
					->where('gruCodi',$this->gruCodi)
					->first();

					if( is_null($familia_current) ){
						$validator->errors()->add('gruCodi','El codigo de la familia/grupo es incorrecto');
					}

					else {

						$familias_group = $famlias->where('gruCodi', $this->gruCodi );
						$familia_with_fanNomb = $familias_group->where('famNomb' , $this->famNomb)->first();
						
						if(!is_null($familia_with_fanNomb)){
							if( $familia_with_fanNomb->famCodi != $familia_current->famCodi ){							$validator->errors()->add('gruCodi','El nombre de la familia esta repetido en este grupo');
							}
						}
					}
			});

		}
	}
}
