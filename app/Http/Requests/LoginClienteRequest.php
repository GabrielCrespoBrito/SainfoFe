<?php

namespace App\Http\Requests;

use App\ClienteProveedor;
use App\Empresa;
use Illuminate\Foundation\Http\FormRequest;

class LoginClienteRequest extends FormRequest
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

	public function rules()
	{
		$rules = [
			'password_cliente' => 'required',
			// 'documento' => 'required|exists:prov_clientes,PCRucc',			
		];


		if(is_online()){
			$rules['ruc_empresa'] = 'required|digits:11|exists:opciones,EmpLin1';
		}


		return $rules;

	}
	
	public function withValidator($validator)
	{
		if( !$validator->fails() ){

			$validator->after(function($validator){

				$empcodi = is_online() ? Empresa::findByRuc($this->ruc_empresa)->empcodi : '001';

				$cliente = ClienteProveedor::where('PCRucc' , $this->documento )
				->where('TipCodi', 'C')
				->where('EmpCodi',  $empcodi )
				->first();

				if( is_null($cliente) ){
					$validator->errors()->add('documento', 'Este cliente esta asociado a otra empresa');					
				}
				else if( $cliente->PCDocu !== $this->password_cliente ){
					$validator->errors()->add('password_cliente', 'La contraseña es incorrecta');					
				}

			}); // after
		}

	}

	public function messages(){
		return [
			'ruc_empresa.exists' => 'Este ruc no esta asociado a ninguna empresa de nuestro sistema',			
			'documento.exists' => 'Este ruc no esta asociado a ninguna cliente de nuestro sistema',
			'ruc_empresa.required' => 'El ruc de la empresa es obligatorio',
			'documento.required' => 'El documento del cliente es obligatorio',
			'password_cliente.required' => 'La contraseña del cliente es obligatoria',			

		];
	}

	
}
