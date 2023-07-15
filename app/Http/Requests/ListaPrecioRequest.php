<?php

namespace App\Http\Requests;

use App\ListaPrecio;
use Illuminate\Foundation\Http\FormRequest;

class ListaPrecioRequest extends FormRequest
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
	public $isPostMethod;

	public function rules()
	{

		$this->isPostMethod =  $this->getMethod() == "POST";

		$rules =  [
			'LisNomb' => 'required|max:100',
		];

		if( $this->isPostMethod ){
			$rules['LocCodi'] = 'required|max:10';
			$rules['LocCodiCopy'] = 'required';
		}


		return $rules;
	}


	public function withValidator($validator){


		if( ! $validator->fails() ){

			$validator->after(function($validator){

				$lista = ListaPrecio::where('LisNomb', $this->LisNomb)->first();
				
				if( ! is_null($lista) ){

					if( $this->isPostMethod ){
          	$validator->errors()->add('LisNomb', "Ya se encuentra repetido este nombre");
						return;
          }

					else {
						$id = $this->route()->parameters['id'];
						if( $id  != $lista->LisCodi ){
          		$validator->errors()->add('LisNomb', "Ya se encuentra repetido este nombre");		
							return;
						}
					}
				}

				// Comprobar que la lista de precios a copiar exista
				if( $this->isPostMethod ){
					if( ListaPrecio::find($this->LocCodiCopy) == null ){
          	$validator->errors()->add('LisCodi', "La lista de precio a copiar no existe");		
						return;
					}
				}

			});

		}
	}
}
