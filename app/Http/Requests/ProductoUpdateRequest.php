<?php

namespace App\Http\Requests;

use App\Familia;
use App\Grupo;
use App\Marca;
use App\Producto;
use Illuminate\Foundation\Http\FormRequest;

class ProductoUpdateRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

  public function prepareForValidation()
  {
    $this->merge(['ProSTem' => (int) $this->has('ProSTem')]);
  }


	public function rules()
	{
		// numero_operacion
		return [
			'grupo'   => 'required',
			'familia' => 'required',            
			'marca'   => 'required',
			'procedencia' => 'required|exists:procedencia,ProcCodi',
			'numero_operacion' => 'required',
      'codigo_barra' => 'nullable|sometimes|max:45',
			'nombre' => 'required:min:1|max:255',
			'tipo_existencia'=> 'required|numeric|exists:tipo_existencia,TieCodi',
			'moneda'  => 'required|numeric|exists:moneda,moncodi',
			'unidad'  => 'required|exists:uniproducto,UnPCodi',
			'imagen'  => 'nullable|sometimes|mimes:png,jpeg,gif',
			'base_igv'=> 'required|in:GRAVADA,INAFECTA,EXONERADA,GRATUITA',
      'isc' => 'required|numeric|min:0',
			'porc_com_vend' => 'required|numeric|min:0|max:100',
			'ProPerc'  => 'sometimes|in:0,1',
			'icbper'  => 'sometimes|in:0,1',
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
				$producto = Producto::find($this->codigo);

				if( is_null($producto) ){
				  $validator->errors()->add('codigo', 'El codigo del producto es requerido');
          return;
				}

				// Validar Marca
				if( is_null($marca) ){
				  $validator->errors()->add('marca', 'El codigo de la marca es requerido');
          return;
				}

				// Validar familia
				if( is_null($familia) ){
				  $validator->errors()->add('familia', 'El codigo de la familia es requerido');
          return;
				}

				// Validar grupo
				if( is_null($grupo) ){
				  $validator->errors()->add('grupo', 'El codigo del grupo es requerido');
          return;
				}

				// $producto_procodi = Producto::findByProCodi($this->numero_operacion);
        $producto_procodi = Producto::withoutGlobalScope('noEliminados')->where('ProCodi', $this->numero_operacion)->first();
				
				if( $producto_procodi ){
					if( $producto->ID != $producto_procodi->ID ){
            $validator->errors()->add('numero_operacion', "El codigo de producto {$this->numero_operacion} ya esta siendo usado por otro producto");
            return;
          }
				}

        else {
          // useInDocument
          $result = $producto->useInDocument();

          if( ! $result->success ){
            $validator->errors()->add('numero_operacion', sprintf("No puede cambiar el codigo del producto ya que esta siendo usando en %s ", $result->sitio  ));
            return;            
          }
        }

        
        // Buscar por codigo de barra
        if ($this->codigo_barra) {
          $producto_codigo_barra = Producto::findByCodigoBarra($this->codigo_barra);
          if ($producto_codigo_barra) {
            if( $producto->ID != $producto_codigo_barra->ID ){
              $validator->errors()->add('codigo_barra', "El codigo de barra {$this->codigo_barra} ya esta siendo usado por otro producto");
              return;
            }
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
}