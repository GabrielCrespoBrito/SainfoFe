<?php

namespace App\Http\Requests;

use App\GuiaSalida;
use Illuminate\Foundation\Http\FormRequest;

class GuiaDespachoRequest extends FormRequest
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
		// @TODO VALIDACIÓN REAL

		return [
			'direccion_llegada'    => 'required|max:255',
			'ubigeo'               => 'required|numeric',            
			'direccion_partida'    => 'required|max:255',
			'empresa'              => 'required',
			'motivo_traslado'      => 'required',
			'transportista'        => 'required',
			'serie_documento'      => 'required',
			'numero_documento'      => 'required',
			'peso_total'      => 'required|numeric|min:1',
		];
	}

	public function withValidator($validator){

		$validator->after(function($validator){

			$id_guia = $this->route()->parameters()['id'];

			$guia = GuiaSalida::find($id_guia);

			if( $guia->isCerrada()){
				$validator->errors()->add('guia' , 'Esta guia ya esta impresa');
			}
		
		});
	}

	public function messages(){
		return [
			'peso_total.numeric' => 'El peso tiene que ser numerico',
			'peso_total.min' => 'El peso tiene que ser mayor que 0',
		];

	}


}
