<?php

namespace App\Http\Requests;
use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class VentaDeleteRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return auth()->user()->isAdmin();
	}

	public function rules()
	{
		return [
			'id_factura' => 'required'
		];
	}
	
	public function withValidator($validator){

		if( !$validator->fails() ){

			$validator->after(function($validator){

				$venta = Venta::find( $this->id_factura );
				
				if( $venta->fe_rpta != 9 ){
					$validator->errors()->add('id_caja', 'No se puede eliminar un documento que ya ha sido enviado');
				}
				
				if ( $venta->isBoleta()) {
					if ( $detalle = $venta->anulacion ) {
						$message = "No se puede eliminar esta boleta porque se encuentra en el resumen ({$detalle->docNume}). <br> <p> Primero procesa a quitar el documento del resumen para poder eliminar esta boleta </p>";
						$validator->errors()->add('id_caja', $message);
					}
				}				
				
			});

		}
	}
}