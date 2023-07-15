<?php

namespace App\Http\Requests;

use App\Familia;
use App\Grupo;
use Illuminate\Foundation\Http\FormRequest;

class FamiliaStoreRequest extends FormRequest
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
			'famCodi' => 'required|digits_between:1,3',      
			'gruCodi' => 'required',
			'famNomb' => 'required|max:200',            
		];
	}

	public function messages(){
		return [
			'gruCodi.exists'   => 'El codigo del grupo no existe',
			'famNomb.required' => 'El nombre de la familia es necesario',
			'famCodi.required' => 'Es necesario el codigo de la familia',
			'famCodi.digits_between' => 'Es codigo no puede tener mas de 3 digitos',			
			'famCodi.numeric' => 'El codigo debe ser numerico',
		];
	}


  public function withValidator($validator)
  {
		$validator->after(function ($validator) {

			$empresa = get_empresa();

			if( is_null(Grupo::find($this->gruCodi)) ){
				$validator->errors()->add('tipo','El codigo del grupo es incorrecto');				
			}

			else {

				$familias = get_empresa()->familias;
				$familia_group = $familias->where('gruCodi', $this->gruCodi);
				
				if( $familia_group->where('famCodi', $this->famCodi)->count() ){
					$validator->errors()->add('tipo','Ya codigo de la familia esta repetido en este grupo');
				}

				else {
					
					$nombre = strtoupper($this->famNomb);

					if( $familia_group->where('famNomb', $nombre)->count() ){
						$validator->errors()->add('tipo','El nombre de la familia esta repetido en este grupo');
					}
				}
			}


		});

  }

}
