<?php

namespace App\Http\Requests;

use App\Unidad;
use Illuminate\Foundation\Http\FormRequest;

class UnidadStoreRequest extends FormRequest
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
			"UniPeso" => "required|numeric|min:0",
			"UniPUCD" => "required|numeric|min:0",
			"UniPUCS" => "required|numeric|min:0",
			"UniMarg" => "required|numeric|min:0",
			"UniPUVD" => "required|numeric|min:0",
			"UNIPUVS" => "required|numeric|min:0",
			"UniPAdi" => "required|numeric|min:0",
			"LisCodi" => "required",
		];
	}

	public function withValidator($validator){


		if( ! $validator->fails() ){

			$validator->after(function($validator){

				$producto_id = $this->route()->parameters()['id_producto'];
				
				// $route_name = $this->route()->getName();

				$unidad = Unidad::where([
					['empcodi', empcodi()] , 
					['Id', $producto_id],
					['LisCodi', $this->ListCodi],
					['UniAbre', $this->UniAbre],
				])->count();

				// dd($producto_id , $this->ListCodi , empcodi() , $this->UniAbre );

				if( $unidad ){
					$validator->errors()->add('LisCodi', 'No puede puede haber unidades repetidas en la misma lista');
				}

			});

		}

	}
}
