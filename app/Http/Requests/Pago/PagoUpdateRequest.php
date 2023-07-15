<?php

namespace App\Http\Requests\Pago;

use App\VentaPago;
use App\Models\Compra\CompraPago;
use App\Moneda;
use Illuminate\Foundation\Http\FormRequest;

class PagoUpdateRequest extends FormRequest
{

	public $documento;
	public $pago;
	public $id_efectivo;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$route = $this->route()->getName();
		$isVenta = strpos($route, 'venta') !== false;
		$id = $this->route()->parameters['id'];
		$this->pago = $isVenta ? VentaPago::findOrfail($id) : CompraPago::findOrfail($id) ;
		$this->documento = $this->pago->getDocumento();
		

		$rules = [
			'moneda' => 'exists:moneda,moncodi',
			'tipocambio' => 'required|numeric|min:1',
			'VtaImpo' => 'required|numeric|min:0.1'
		];

		if ($this->pago->isBancario()) {
			$rules['cuenta_id'] = 'required';
			$rules['baucher'] = 'required';
			$rules['fecha_pago'] = 'required|date';
			// $rules['fecha_emision'] = 'required|date';
		}

		return $rules;
	}

	public function withValidator($validator)
	{
		if (!$validator->fails()) {
			// if (true) {

			$validator->after(function ($validator) {

				$caja = $this->pago->caja;

				if (!$caja->isAperturada()) {
					$validator->errors()->add('deuda', "La caja ({$caja->CajNume}) del pago esta cerrada");
					return;
				}
				
				# Calcular que el monto suministrado no supere a la deuda

				$pago = $this->pago;
				$request_moneda_id  = $this->moneda;
				$request_importe  = $this->VtaImpo;


				// Si es la misma moneda y el monto es el mismo o inferior entonces no hay nadad que calcular
				if( $pago->MonCodi == $request_moneda_id && 
				($pago->PagImpo == $request_importe || $request_importe <= $pago->PagImpo) ){
					return;
				}

				$documento = $this->documento;
				$monedaDocumento = $documento->getMoneda();

				// Restar al documento el pago actual para luego calcular si el nuevo monto es superior a la deuda
				if( $monedaDocumento == $pago->MonCodi ){
					$montoRestar = $pago->PagImpo;
				}
				else {
					$montoRestar = $pago->MonCodi == Moneda::DOLAR_ID ? ($pago->PagImpo * $pago->PagTCam) : ($pago->PagImpo / $pago->PagTCam);
				}
				
				// Saldo real del documento
				// $saldoDocumento = $documento->saldo - $montoRestar;
				$saldoDocumento = $documento->saldo + $montoRestar;
        // $saldoDocumento = $documento->saldo;

				// Si la moneda del documento es la misma del "nuevo" pago				
				if( $monedaDocumento == $request_moneda_id  ){
					if(  $request_importe > $saldoDocumento ){
						$validator->errors()->add('deuda', 'El monto suministrado supera a la deuda');
					}
					return;
				}

				// Si no es la misma moneda hacemos la conversión
				else {
					$monto = $request_moneda_id == Moneda::DOLAR_ID ? ($request_importe * $this->tipocambio) : ($request_importe / $this->tipocambio);

					if( $monto > $saldoDocumento ){
						$validator->errors()->add('deuda', 'El monto suministrado supera a la deuda');
					}

					return;
				}



			});
		}
	}

	public function messages()
	{
		return [
			'PagOper.unique' => 'El numero de pago ya ha sido tomado, refresque la pagina',
			'VtaImpo.numeric' => 'El importe debe ser númerico',
			'VtaImpo.min' => 'El importe debe ser minimo de :min',
		];
	}
}
