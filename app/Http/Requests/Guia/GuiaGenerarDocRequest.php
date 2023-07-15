<?php

namespace App\Http\Requests\Guia;

use App\GuiaSalida;
use App\M;
use App\TipoDocumento;
use Illuminate\Foundation\Http\FormRequest;

class GuiaGenerarDocRequest extends FormRequest
{
	public $guia;

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
		$id = $this->route()->parameters()['id'];
		$this->guia = $guia = GuiaSalida::find( $id );

		$rules = [
			"gen_fecha" => "required|before_or_equal:" . date('Y-m-d'),
			"gen_tdoc" => "required|in:01,03",
		];
		
		if( $guia->isIngreso() ){
			$rules['gen_serie'] = 'required|max:4';
			$rules['gen_num'] = 'required';
		 }
		 else {
			$rules['gen_serie'] = 'required';
		 }

		return $rules;	
	}


	public function withValidator( $validator )
	{
		if( ! $validator->fails() ){

			$validator->after(function($validator){

				$cliente = $this->guia->cliente;	

				if( $this->gen_tdoc === "01" ){
					if( !$cliente->isRuc() ){
						$validator->errors()->add('gtdoc', 'Para generar una factura, el cliente tiene que ser un cliente con ruc');
						return;
					}
				}

				if ( $this->gtdoc === "01" || $this->gtdoc === "03" ) {
					if (  !  ($cliente->isDni() || $cliente->isOtros()) ) {
						$validator->errors()->add('gtdoc', 'Para una boleta el cliente tiene que tener DNI');
						return;
					}
				}
			});
		}

	}

		
}
