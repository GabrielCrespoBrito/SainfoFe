<?php

namespace App\Http\Requests\Cuenta;

use App\BancoEmpresa;
use App\Repositories\BancoCuentaRepository;
use Illuminate\Foundation\Http\FormRequest;

class CuentaRequest extends FormRequest
{
	public $isStore;

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
		$this->isStore = $this->getMethod() == 'POST';

		// dd( $this->getMethod() );
		return [
			'CueNume' => 'required',
			'BanCodi' => 'required|exists:bancos,bancodi',
			'MonCodi' => 'required|in:01,02',
			'Detract' => 'required|in:0,1',
		];
	}

	public function withValidator($validator)
	{
		if( !$validator->fails() ){
			$validator->after(function($validator){

				$banco = BancoEmpresa::where('CueNume', $this->CueNume)
				->where('BanCodi', $this->BanCodi)
				->first();

				if( $this->isStore ){
					if( $banco ){
						$validator->errors()->add('CueNume', 'El número de cuenta esta repetido');
					}
				}

				else {
					$cuentaCurrent = BancoEmpresa::findOrfail( $this->route()->parameters()['cuentum']);

					if( $banco ){
						if( $banco->CueCodi != $cuentaCurrent->CueCodi ){
							$validator->errors()->add('CueNume', 'El número de cuenta esta repetido');
						}
					}
				}
			});
		}
	}
}
