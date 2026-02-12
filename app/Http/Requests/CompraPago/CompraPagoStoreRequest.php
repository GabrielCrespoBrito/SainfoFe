<?php

namespace App\Http\Requests\CompraPago;

use App\Caja;
use App\Compra;
use App\TipoPago;
use App\BancoEmpresa;
use Illuminate\Foundation\Http\FormRequest;

class CompraPagoStoreRequest extends FormRequest
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
		$this->isBancario = TipoPago::isTipoBanco($this->tipopago);

		$rules = [
			'VtaOper' => 'required',
			'VtaImpo' => 'required|numeric|min:0',
			'tipocambio' => 'required|numeric|min:0',
		];

		if ( $this->isBancario ) {
			$rules['cuenta_id'] = 'required';
			$rules['baucher'] = 'required';
      $rules['fecha_pago'] = 'required';
    } 
    else if ($this->tipopago == TipoPago::NOTACREDITO) {
      $rules['nota_credito_id'] = 'required';
    }
    

		return $rules;
	}

	public function withValidator($validator)
	{
		$compra = Compra::findOrfail($this->VtaOper);

		if (!$validator->fails()) {

			$validator->after(function ($validator) use ($compra) {

        if (!Caja::hasAperturada(null, get_empresa()->isTipoCajaLocal())) {
          $validator->errors()->add('deuda', 'No tiene ninguna caja aperturada');
          return;
        }

        if ($compra->deudaSaldada()) {
          $validator->errors()->add('deuda', 'Esta compra ya ha sido completamente pagada');
          return;
        }

				// Si es bancario
				if( $this->isBancario ){

					$banco_cuenta = BancoEmpresa::find($this->cuenta_id);
					if( is_null($banco_cuenta) ){
						$validator->errors()->add('deuda', 'La cuenta de bancaria no existe');
						return;
					}
          
          
					$caja = Caja::where('CueCodi', $this->cuenta_id)->first();


          if( is_null($caja) ){
						$validator->errors()->add('deuda', 'La cuenta de banco no tiene aperturada ninguna caja');
						return;
					}
				}


        if ($this->tipopago == TipoPago::NOTACREDITO) {

          $nota_credito = Compra::find($this->nota_credito_id);

          if (is_null($nota_credito)) {
            $validator->errors()->add('deuda', 'La nota de credito seleccionada no existe');
            return;
          }

          if ($compra->isNotaCredito()) {
            $validator->errors()->add('deuda', 'No puede pagar la nota de credito con otra nota de credito');
            return;
          }

          if (!$nota_credito->isNotaCredito()) {
            $validator->errors()->add('deuda', 'El documento seleccionada no una nota de credito');
            return;
          }

          if (!$nota_credito->CpaImpo) {
            $validator->errors()->add('deuda', 'El documento seleccionada no una nota de credito');
            return;
          }

          if ( ! $nota_credito->montoPagoValid($this->VtaImpo, $this->moneda, $this->tipocambio)) {
            $validator->errors()->add('deuda', 'El monto no puede ser superior al de la nota de credito que hace referencia');
            return;
          }
        }


    		$moneda = $this->moncodi ?? $this->moneda ?? '01';
				if ( ! $compra->montoPagoValid($this->VtaImpo, $moneda, $this->tipocambio) ) {
					$validator->errors()->add('deuda', 'El monto suministrado supera a la deuda');
					return;
				}

			});

		}
	}

	public function messages()
	{
		return ['PagOper.unique' => 'El numero de pago ya ha sido tomado, refresque la pagina'];
	}
}
