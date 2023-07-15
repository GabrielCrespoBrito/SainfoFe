<?php

namespace App\Http\Requests\Contingencia;

use App\Models\Contingencia\Contingencia;
use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class ContingenciaStoreRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */

	public $isCreate;

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
		$this->isCreate = $this->route()->getName() == "contingencia.store";

		$rules = [
			'items.*.vtaoper' => 'required',
			'items.*.motivo' => 'required|exists:contingencias_motivos,id',
		];

		if( $this->isCreate ){
			$rules['fecha'] = 'required|date|before_or_equal:' . date('Y-m-d');
		}
		else {
			$rules['ticket'] = 'nullable|sometimes|max:30';		
		}

		return $rules;
	}

	public function withValidator($validator)
	{
		if( ! $validator->fails() ){
			$contingencia = null;


			if( ! $this->isCreate ){
				$id = $this->route()->parameters['id'];
				$contingencia = Contingencia::findOrfail($id);
			}
			
			$validator->after(function($validator) use( $contingencia ){

				if( ! $this->isCreate && optional($contingencia)->hasTicket() ){
						$validator->errors()->add("vtaoper","Esta contingencia esta cerrada");
						return;
				}

				foreach ($this->items as $item) {
					$venta = Venta::find($item['vtaoper']);
					

					if( is_null($venta) ){
						$validator->errors()->add("vtaoper","El documento no existe");
						return;
					}

					if( ! $venta->isContingencia() ){
						$validator->errors()->add("vtaoper","La serie del documento agregado no es de contingencia");
						return;
					}

					$detalle = $venta->contingenciaDetalle;


					if( $detalle ){
						if( $this->isCreate ){
							$validator->errors()->add("vtaoper","El documento {$venta->VtaOper} ya se encuentra en otro resumen de contingencia");
						}
						else {

							if( $contingencia->id != $detalle->con_id ){
								$validator->errors()->add( "vtaoper" , "Este documento ya se encuentra en otro resumen de contingencia" );
								return;
							}
						}

					}					

				}

			});
		}
	}

}
