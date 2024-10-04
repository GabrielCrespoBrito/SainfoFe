<?php

namespace App\Http\Requests;

use App\Familia;
use App\Grupo;
use App\Producto;
use Illuminate\Foundation\Http\FormRequest;

class GrupoDeleteRequest extends FormRequest
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
			'id' => 'required',
		];
	}

	public function withValidator($validator)
	{
		if( !$validator->fails() ){
			
			$validator->after(function ($validator){

				$id = $this->id;
				// $grupos = Grupo::where('empcodi', empcodi())->get();
				$grupo_current = Grupo::find($id);

				if( is_null($grupo_current) ){
					$validator->errors()->add('MarCodi', 'El codigo del grupo es incorrecto' );
				}

			});    
		
		}
	}
	
}
