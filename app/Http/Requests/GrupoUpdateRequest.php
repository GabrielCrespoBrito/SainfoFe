<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Grupo;

class GrupoUpdateRequest extends FormRequest
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
			'GruCodi' => 'required',
			'GruNomb' => 'required|max:50',			
		];
	}

	public function withValidator($validator)
	{
		if( !$validator->fails() ){
			
			$validator->after(function ($validator){

				$grupos = Grupo::where('empcodi', empcodi())->get();
				$grupo_current = Grupo::find($this->GruCodi);

				if( is_null($grupo_current) ){
					$validator->errors()->add('MarCodi', 'El codigo del grupo es incorrecto' );
				}

				else {
					$nombre = strtoupper($this->GruNomb);
					$grupo_withName = $grupos->where('GruNomb', $nombre )->first();

					if( !is_null($grupo_withName)  ){
						if( $grupo_withName->GruCodi !== $grupo_current->GruCodi ){
							$validator->errors()->add('GruNomb', 'El nombre (' .  $nombre .  ') esta repetido' );
						}

					}

				}

			});    
		
		}
	}

	public function messages(){
		return [
			'GruNomb.required' => 'Es nombre es obligatorio'
		];
	}

}


