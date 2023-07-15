<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumenDeleteRequest extends FormRequest
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
			'id_resumen' => 'required',
			'docnume' => 'required'
		];
	}

	public function withValidator($validator){

		if( ! $validator->fails() ){

			$validator->after(function($validator){

				$resumen = Resumen::findMultiple( $this->id_resumen , $this->docnume );

				if( is_null($resumen) ){
						$validator->errors()->add('doc', 'No se puede eliminar un resumen que ya contenga un Ticket');					
				}
				elseif( $resumen->DocTicket ) {
						$validator->errors()->add('doc', 'No se puede eliminar un resumen que ya contenga un Ticket');
				}

			});

		}
	}

	
}
