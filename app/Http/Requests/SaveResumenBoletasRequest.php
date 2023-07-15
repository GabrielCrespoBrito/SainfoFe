<?php

namespace App\Http\Requests;

use App\Venta;
use App\find;
use Illuminate\Foundation\Http\FormRequest;

class SaveResumenBoletasRequest extends FormRequest
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
		];
	}


	public function withValidator($validator)
	{
		$validator->after(function ($validator) {

			$numero_anterior = 0;
			
			// Comprobar correlatividad
			for( $i = 0; $i < count($this->ids) ; $i++) {

				$venta = Venta::find( $this->ids[$i] );

				$id = (int) $venta->VtaNumee;

				// El primer id no se toma en cuanto para la comparación de correlativos
				if( $i && $id != ($numero_anterior + 1) ){
          $validator->errors()->add(
						'corre', 
						'Los números tiene que ser correlativos (' . 
						implode( '-', $this->ids) . 
						')' 
					);		
          break;
				}

				$numero_anterior = $id;
			}



		});         
	}    
}


