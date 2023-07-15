<?php

namespace App\Http\Requests;

use App\Producto;
use App\Vendedor;
use App\FormaPago;
use App\GuiaSalida;
use App\ClienteProveedor;
use Illuminate\Foundation\Http\FormRequest;

class GuiaUpdateRequest extends FormRequest
{
	public $isCreate;
	public $guia;
	public $guia_id;
	public $estado_edicion;

	public function prepareForValidation()
	{
		$this->isCreate = $this->route()->getName() != 'guia.update';

		if ( ! $this->isCreate ) {
			$this->guia_id =  $this->route()->parameters()['id'];
			$this->guia = GuiaSalida::findOrfail($this->guia_id);
			$this->estado_edicion = $this->guia->getEstadoEdicion();
		}
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if($this->isCreate){
			return true;
		}

		return !($this->estado_edicion === GuiaSalida::ESTADO_EDIT_CLOSED);
	}



	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

		if( $this->isCreate || $this->estado_edicion == GuiaSalida::ESTADO_EDIT_OPEN ){
			return GuiaSaveRequest::RULES;
		}

		return GuiaSaveRequest::RULES_OPEN_PRICE;	

	}


	/**
	 * Validar el formulario completo de la guia
	 *
	 * @param [type] $validator
	 * @return void
	 */
	public function validateFullForm(&$validator)
	{
		// Validar Cliente
		$cliente = ClienteProveedor::findByTipo($this->cliente_documento, ClienteProveedor::TIPO_CLIENTE);

		if (is_null($cliente)) {
			$validator->errors()->add('cliente_documento', 'No existen un cliente registrado en esta empresa con ese ruc');
			return;
		} elseif (!$cliente->isCliente()) {
			$validator->errors()->add('cliente_documento', 'El documento no pertenece a un cliente si no de un proveedor');
			return;
		}

		// Validar Forma de pago
		$forma_pago = FormaPago::find($this->forma_pago);
		if (is_null($forma_pago)) {
			$validator->errors()->add('forma_pago', 'Esta forma de pago no existe ');
		}

		// Validar Vendedor
		$vendedor = Vendedor::find($this->vendedor);
		if (is_null($vendedor)) {
			$validator->errors()->add('vendedor', 'Este vendedor no existe');
		}

		// Validar items
		$items = collect($this->items);

		foreach ($items as $item) {
			$producto = Producto::where('ProCodi', $item['DetCodi'])->first();
			if (is_null($producto)) {
				$validator->errors()->add('DetCodi', 'El codigo de producto es incorrecto');
				return;
			}
			$unidad = $producto->unidades->where('Unicodi', $item['UniCodi'])->first();
			if (is_null($unidad)) {
				$validator->errors()->add('UniCodi', 'El codigo de la unidad es incorrecto');
				return;
			}
		}


	}


	public function withValidator($validator)
	{
		if (!$validator->fails()) {

			$validator->after(function ($validator) {

				if( $this->isCreate || $this->estado_edicion == GuiaSalida::ESTADO_EDIT_OPEN ){
					$this->validateFullForm($validator);
					return;
				}

				$lineas = $this->guia->items->pluck('Linea')->toArray();
				
				foreach ( $this->items  as $item ) {

					if(!in_array($item['Linea'] , $lineas) ){
						$validator->errors->add('No se puede eliminar ni agregar ningun item a la guia');
						break;
					}
				}
			});
		}
	}



}
