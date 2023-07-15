<?php

namespace App\Http\Requests;

use App\ClienteProveedor;
use App\ContrataEntidad;
use Illuminate\Foundation\Http\FormRequest;

class ClienteProveedorEliminarRequest extends FormRequest
{
	/**
  * Determine if the user is authorized to make this request.
  *
  * @return bool
  */
	public function authorize()
	{
		return $this->user()->can('delete clientes');
	}

	/**
  * Get the validation rules that apply to the request.
  *
  * @return array
  */
	public function rules()
	{
		return [
			'codigo' => 'required',
			'tipo' => 'required',
		];
	}


	public function getMessage($entidad)
	{
		return "La entidad tiene {$entidad} asociadas por lo tanto no puede eliminarse. Primero debe eliminar el/los documentos asociados, para proceder a la eliminación";
	}

	public function withValidator($validator) 
  {
    if( !$validator->fails() ){

      $validator->after(function ($validator) {
        $cliente = ClienteProveedor::findByTipo($this->codigo, $this->tipo );

        if ( $cliente ) {

          if ( $cliente->isDefaultUser() ) {
            $validator->errors()->add('codigo', 'Los clientes/proveedores por defecto del sistema no se puede eliminar' );
            return;
          }

          switch( $cliente->TipCodi )
          {
            // Cliente
            case ClienteProveedor::TIPO_CLIENTE:
              if ( $cliente->ventas->count() ) {
                $validator->errors()->add('codigo', $this->getMessage('ventas'));
                return;
              }		
            break;

            // Proveedor
            case ClienteProveedor::TIPO_PROVEEDOR:
              if ( $cliente->compras->count() ) {
                $validator->errors()->add('codigo', $this->getMessage('compras'));
                return;
              }
            break;
          }


          if ( $cliente->cotizaciones->count() ) {
            $validator->errors()->add('codigo', $this->getMessage('cotizaciones'));
            return;
          }

          if ( $cliente->guias->count() ) {
            $validator->errors()->add('codigo', $this->getMessage('compras'));
            return;
          }

          if (  ContrataEntidad::where('entidad_id', $cliente->PCRucc)->count()) {
            $validator->errors()->add('codigo', 'No se puede eliminar este cliente por que tiene un contrato asociado');
            return;
          }
        }
      });
    }
	}
}
