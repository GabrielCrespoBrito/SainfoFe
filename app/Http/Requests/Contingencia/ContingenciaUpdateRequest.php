<?php

namespace App\Http\Requests\Contingencia;

use Illuminate\Foundation\Http\FormRequest;

class ContingenciaUpdateRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
			return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
			return [
					'items.*.vtaoper' => 'required',
					'items.*.motivo' => 'required|exists:contingencias_motivos,id',
			];
	}

	public function withValidator($validator)
	{
		if( ! $validator->fails() ){

			$validator->after(function($validator){

				foreach ($this->items as $item) {
					$venta = Venta::find($item['vtaoper']);

					if( is_null($venta) ){
						$validator->errors()->add("vtaoper','El documento {$item['vtaoper']} no existe");
					}

					else {
						if( $venta->fe_rpta == 0 ){
							$validator->errors()->add("vtaoper','El documento {$item['vtaoper']} ya ha tiene un ticket");
						}
					}
				}
				
			});
		}
	}

}
