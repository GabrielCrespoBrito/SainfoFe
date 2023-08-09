<?php

namespace App\Http\Requests;

use App\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ProduccionRequest extends FormRequest
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
      'manFechEmis' => 'required|date',
      'manFechVenc' => 'required|gte:manFechEmis',
      'producto_final_id' => 'required',
      'producto_final_cantidad' => 'required',
      'manResp' => 'sometimes|max:50',
      'manDeta' => 'sometimes|max:200',
      'producto_insumo_id.*' => 'required',
      'producto_insumo_cantidad.*' => 'required|numeric|min:0|integer',
    ];
  }

  public function validateProductoFinal(&$validator)
  {
    if(!Producto::findByProCodi($this->producto_final_id) ){
      $validator->errors()->add('producto_final_id', 'El Codigo del Producto Final No Existe');
      return false;
    }

    return true;
  }

  public function validateProductoInsumos(&$validator)
  {
    if (count($this->producto_insumo_id) != count($this->producto_insumo_cantidad) ){
      $validator->errors()->add('producto_insumo_cantidad', 'Faltan Datos de los Productos de insumos');
      return false;
    }

    foreach($this->producto_insumo_id as $productoInsumoId){
      if (!Producto::findByProCodi($productoInsumoId)) {
        $validator->errors()->add('producto_final_id', sprintf('El Codigo del Producto de insumo %s No existe', $productoInsumoId ));
        return false;
      }
    }

    if(count($this->producto_insumo_id) != count(array_unique($this->producto_insumo_id))){
      $validator->errors()->add('producto_final_id', sprintf('Hay Productos de Insumos Repetidos', $productoInsumoId));
      return false; 
    }

    if(in_array($this->producto_final_id, $this->producto_insumo_id)) {
      $validator->errors()->add('producto_final_id', 'El Producto Final no puede estar en los Productos de Insumos');
      return false;
    }

    return true;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        if( !$this->validateProductoFinal($validator) ){
          return;
        }

        if (!$this->validateProductoInsumos($validator)) {
          return;
        }
      });
    }
  }
}
