<?php

namespace App\Http\Requests;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class VentaPendienteRequest extends FormRequest
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
		];
	}

	public function withValidator($validator){

		$validator->after(function($validator){

			$id_factura = $this->id_factura;

			$venta = Venta::find($id_factura);

			if ( $venta->VtaFMail != StatusCode::CODE_ERROR_0011  ) {
				$validator->errors()->add('error', "El documento {$venta->VtaNume} no esta en estado PENDIENTE");
				return;
			}

			if( $venta->isContingencia() ){
				$validator->errors()->add( 'error' , 'Este es un documento de contingencia, tiene que guardarlo en un resumen de contingencia' );
				return;
			}
			
			if( $venta->isNotaCredito() && $venta->docRefIsBoleta() ){
				
				if( ! is_ose() ){
					if( $venta->anulacion ){
						$validator->errors()->add( 'error' , 'Esta nota de credito, ya tiene un resumen asociado, por favor enviee el resumen desde el area de resumenes' );
					}
				}
				
			}

		});

	}
}
