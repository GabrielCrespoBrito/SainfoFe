<?php

namespace App\Http\Requests;

use App\Vendedor;
use Illuminate\Foundation\Http\FormRequest;

class VendedorDestroyRequest extends FormRequest
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

	public function withValidator($validator)
	{
		// dd($this->route()->parameters());

		$validator->after(function ($validator) {
			$vendedor = Vendedor::findOrfail($this->route()->parameters()['vendedore']);

			if ($vendedor->ventas->count()) {
				$validator->errors()->add('CueNume', 'No puede eliminarse este vendedor, porque tiene ventas asociadas');
				return;
			}

			if ($vendedor->compras->count()) {
				$validator->errors()->add('CueNume', 'No puede eliminarse este vendedor, porque tiene compras asociadas');
				return;
			}

			if ($vendedor->guias->count()) {
				$validator->errors()->add('CueNume', 'No puede eliminarse este vendedor, porque tiene guias asociadas');
				return;
			}
		});
	}
}
