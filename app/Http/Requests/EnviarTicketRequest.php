<?php

namespace App\Http\Requests;

use App\Resumen;
use Illuminate\Foundation\Http\FormRequest;

class EnviarTicketRequest extends FormRequest
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
			'id_resumen' => 'required' , 
			'docnume' => 'required',
		];
	}

	public function withValidator($validator){
		
		$resumen = Resumen::findMultiple( $this->id_resumen, $this->docnume, empcodi());
		

		$validator->after(function ($validator) use ($resumen){

			if( !$resumen->DocTicket ){
				
				$validator->errors()->add('Ticket', 'Este resumen no tiene un ticket que validar todavia');
			}

		});

	}

}
