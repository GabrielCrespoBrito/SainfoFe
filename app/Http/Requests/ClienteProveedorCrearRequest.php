<?php

namespace App\Http\Requests;
use App\ClienteProveedor;
use App\TipoDocumento;

use Illuminate\Foundation\Http\FormRequest;

class ClienteProveedorCrearRequest extends FormRequest
{
	public $isStore;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$this->isStore = $this->route()->getName() != 'clientes.edit';

		$rules = [
			'tipo_documento' => 'required|exists:prov_clientes_tipo_doc,TDocCodi',
			'tipo_cliente'  => 'required|exists:prov_clientes_tipo,TippCodi',
			'direccion_fiscal'   => 'nullable|max:200',			
			'razon_social'   => 'required|max:100',            
			'ubigeo' 		 => 'nullable|sometimes',
			'telefono_1' => 'nullable|numeric',
			'email'      => 'nullable|email',
      'vendedor'   => 'required',
      'ZonCodi'   => 'required',
			'moneda'     => 'nullable|exists:moneda,moncodi',
			'lista_precio' => 'required',
		];

		// Creando
		if( ! $this->isStore ){
			$rules['codigo'] = 'required';
		}	

		// Dependiendo del tipo de documento del cliente
		if( $this->tipo_documento == TipoDocumento::DNI ){
			$rules['ruc'] = 'required|numeric|digits:8';
		}
		
		elseif( $this->tipo_documento == TipoDocumento::RUC ){
			$rules['ruc'] = 'required|numeric|digits:11';
		}
		
		elseif( $this->tipo_documento == TipoDocumento::NINGUNA ){
			$rules['ruc'] = 'nullable|sometimes|numeric';
		}		
		
		else {
			$rules['ruc'] = 'required|numeric';
		}

		return $rules;
	}


	public function withValidator($validator)
	{
		if( ! $validator->fails() ){

			$validator->after(function ($validator){        

        $cliente_current = ClienteProveedor::findByRuc($this->ruc, null,  $this->tipo_cliente);
        
				# Si se esta creando

				if( $this->isStore ){

					# Si es cualquier otro tipo de documento que no sea el de NINGUNO
					if ($this->tipo_documento != TipoDocumento::NINGUNA) {
						if ($cliente_current) {
							$validator->errors()->add('ruc', "El documento ({$this->ruc}) ya esta registradó");
							return;
						}
					}        
				}

				# Si es modificación
				else {

					$cliente_edit = ClienteProveedor::findByTipo( $this->codigo, $this->tipo_cliente);

					if( !$cliente_edit  ){
						$validator->errors()->add('codigo', "Codigo incorrecto");
						return;
					}


					if( $cliente_edit->canEditDoc() ){

						if ($this->tipo_documento != TipoDocumento::NINGUNA) {

							if ($cliente_current) {

								if ( $cliente_current->PCCodi != $cliente_edit->PCCodi ) {
									$validator->errors()->add('ruc', "El documento ({$this->ruc}) ya esta registradó");
									return;
								}

							}
						}            
					}

				}

			});
		}
	}
}