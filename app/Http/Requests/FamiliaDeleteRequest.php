<?php

namespace App\Http\Requests;

use App\Familia;
use App\Producto;
use Illuminate\Foundation\Http\FormRequest;

class FamiliaDeleteRequest extends FormRequest
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
			//
		];
	}

	public function withValidator($validator){

		if( !$validator->fails() ){

			$error = false;

	   	$familia = Familia::where('famCodi' , $this->id)
	    ->where('gruCodi', $this->id_grupo)
	    ->where('empcodi', empcodi())   
	    ->first();

	    if( is_null($familia)){
	    	$validator->errors()->add('error', 'El codigo de la familia/grupo es incorrecto');
	    }

			
		}

	}

}
