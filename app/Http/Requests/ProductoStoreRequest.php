<?php

namespace App\Http\Requests;

use App\Familia;
use App\Grupo;
use App\Marca;
use App\Producto;
use Illuminate\Foundation\Http\FormRequest;

class ProductoStoreRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function codigoValid(){

	}

  public function prepareForValidation()
  {
    $this->merge(['ProSTem' => (int) $this->has('ProSTem') ]);
  }


	// public function authorize()
	// {
	// 	return auth()->user()->can('create productos');
	// }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'grupo'   => 'required',
			'familia' => 'required',            
			'marca'   => 'required',
			'procedencia' => 'required|exists:procedencia,ProcCodi',
      'numero_operacion' => 'required',
      'codigo_barra' => 'nullable|sometimes|max:45',
			'nombre' => 'required|min:1|max:255',
			'tipo_existencia'=> 'required|numeric|exists:tipo_existencia,TieCodi',
			'moneda'  => 'required|numeric|exists:moneda,moncodi',
			'unidad'  => 'required|exists:uniproducto,UnPCodi',
			'imagen'  => 'nullable|sometimes|mimes:png,jpeg,gif',
			'base_igv'=> 'required|in:GRAVADA,INAFECTA,EXONERADA,GRATUITA',
      'ProSTem' => 'required|in:0,1',
      'precio_min_venta' => 'required|numeric|min:0|max:' . $this->precio_venta,
			'costo' => 'required|min:0|numeric',
			'utilidad' => 'required|numeric|min:0',
			'precio_venta' => 'required|numeric|min:0',
			'peso' => 'required|numeric|min:0',
			'isc'  => 'required|numeric|min:0',
			'icbper' => 'sometimes|in:0,1',
			'ProPerc' => 'sometimes|in:0,1',

			'ubicacion' => 'nullable|max:200',
			'stock_minimo' => 'required_if:ProSTem,1|numeric|min:0',
			'cuenta_venta' => 'nullable|sometimes|digits_between:1,20',
			'cuenta_compra' => 'nullable|sometimes|digits_between:1,20',
			'descripcion'  => 'nullable|max:255',            
			'modo_uso'     => 'nullable|max:255',            
			'ingredientes' => 'nullable|max:255',            
		];
	}

	public function withValidator($validator)
	{

		if( !$validator->fails() ){

			$validator->after(function ($validator) {

				$familia = Familia::find($this->familia);
				$grupo = Grupo::find($this->grupo);
				$marca = Marca::find($this->marca);

				// Validar Marca
				if( is_null($marca) ){
				  $validator->errors()->add('marca', 'El codigo de la marca es incorreto');
				}

				// Validar familia
				if( is_null($familia) ){
				  $validator->errors()->add('familia', 'El codigo de la familia es incorreto');
				}

				// Validar grupo
				if( is_null($grupo) ){
				  $validator->errors()->add('grupo', 'El codigo del grupo es incorreto');
				}


				if ($this->codigoValid()) {
					$validator->errors()->add('codigo', 'El codigo no esta correctamente escrito');
				}


        $producto = Producto::withoutGlobalScope('noEliminados')->where('ProCodi', $this->numero_operacion)->first();

        if($producto){
          $message = $producto->isEliminado() ? "El Codigo {$this->numero_operacion} ya esta utilizado por un producto eliminado" : "El Codigo {$this->numero_operacion} ya esta utilizado por un producto" ;
					$validator->errors()->add('numero_operacion', $message );
				}

        // Buscar por codigo de barra
        if ( $this->codigo_barra ) {
          if (Producto::findByCodigoBarra($this->codigo_barra)) {
            $validator->errors()->add('codigo_barra', "El codigo de barra {$this->codigo_barra} ya esta siendo usado por otro producto");
          }
        }
			});

		}
	}

	public function messages(){
		return [
			'nombre.required' => 'Es requerido el nombre del producto'
		];
	}
	//    
}
