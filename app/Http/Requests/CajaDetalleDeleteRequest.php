<?php

namespace App\Http\Requests;

use App\CajaDetalle;
use Illuminate\Foundation\Http\FormRequest;

class 
CajaDetalleDeleteRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'id_movimiento' => 'required',
		];
	}

	public function withValidator($validator){


		if( ! $validator->fails() ){
			
			$caja_detalle = CajaDetalle::findOrfail($this->id_movimiento);

			$validator->after(function($validator)use($caja_detalle){
				
				$caja = $caja_detalle->caja;

				if( $caja->EmpCodi !== empcodi() || $caja->UsuCodi !== usucodi() ){
					$validator->errors()->add('caja' , 'No puede eliminar un registro de otra caja');
					return;
				}

				if( ! $caja->isAperturada()  ){
					$validator->errors()->add('caja' , 'No puede eliminar un movimiento de una caja cerrada');
					return;
				}

				if( $caja_detalle->isIngresoVenta()  ){
					$validator->errors()->add('caja' , 'No puede eliminar un pago de venta');
					return;
				}

				if(  $caja_detalle->isEgresoCompra()  ){
					$validator->errors()->add('caja' , 'No puede eliminar un pago de compra');
					return;
				}


			});
		}
	}
}