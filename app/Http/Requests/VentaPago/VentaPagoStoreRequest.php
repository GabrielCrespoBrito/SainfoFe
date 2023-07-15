<?php

namespace App\Http\Requests\VentaPago;

use App\Caja;
use App\Venta;
use App\TipoPago;
use Illuminate\Foundation\Http\FormRequest;

class VentaPagoStoreRequest extends FormRequest
{   
	public $venta; 
	public $id_efectivo;
	public $isBancario;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$this->id_efectivo = TipoPago::idEfectivo();
		$this->isBancario = in_array($this->tipopago, TipoPago::TYPE_BANCO);

		$rules = [
			'VtaOper' => 'required',
			'VtaImpo' => 'required|numeric|min:0',
			'VtaNume' => 'nullable|sometimes',
			'moneda' => 'exists:moneda,moncodi' 
    ];

    if( $this->isBancario ){
      $rules['cuenta_id'] = 'required';
      $rules['baucher'] = 'required';
      $rules['fecha_pago'] = 'required';
      // $rules['fecha_emision'] = 'required';
    }
    else if( $this->tipopago == TipoPago::NOTACREDITO ){
      $rules['nota_credito_id'] = 'required';
    }

		return $rules;
	}

	public function withValidator($validator)
	{
		$venta = Venta::findOrfail($this->VtaOper);

		if( ! $validator->fails() )	{
			
			$validator->after(function ($validator) use ($venta) {
				
				if ( ! Caja::hasAperturada(null, get_empresa()->isTipoCajaLocal()) ) {
					$validator->errors()->add('deuda', 'No tiene ninguna caja aperturada');
					return;
				}

        if ($venta->isCanje()) {
          $validator->errors()->add('deuda', 'Un Documento de Canje No es Necesario Registrar Su Pago');
          return;
        }

				if ( $venta->deudaSaldada() ) {
					$validator->errors()->add('deuda', 'El Documento ya ha sido cancelado');
					return;
				}
        
        if ($this->tipopago == TipoPago::NOTACREDITO) {
          $nota_credito = Venta::find($this->nota_credito_id);

          if ( is_null($nota_credito) ) {
            $validator->errors()->add('deuda', 'La nota de credito seleccionada no existe');
            return;
          }

          if ( $venta->isNotaCredito() ) {
            $validator->errors()->add('deuda', 'No puede pagar la nota de credito con otra nota de credito');
            return;
          }

          if(!  $nota_credito->isNotaCredito() ){
            $validator->errors()->add('deuda', 'El documento seleccionada no una nota de credito');
            return;
          }

          if (! $nota_credito->VtaImpo ) {
            $validator->errors()->add('deuda', 'El documento seleccionada no una nota de credito');
            return;
          }
          
          if ( $nota_credito->deudaMenor( $this->VtaImpo, $this->moneda, $this->tipocambio) ) {
            $validator->errors()->add('deuda', 'El monto no puede ser superior al de la nota de credito que hace referencia');
            return;
          }
        }

				if( $venta->deudaMenor( $this->VtaImpo , $this->moneda , $this->tipocambio ) ) {
					$validator->errors()->add('deuda', 'El monto suministrado supera a la deuda');
					return;
				}	    
						
			});			
		}
	}

  public function messages()
  {
		return [ 
			'PagOper.unique' => 'El numero de pago ya ha sido tomado, refresque la pagina' ,
			'baucher.required' => 'El nro de váucher es necesario ' 
			
		];
	}
}
