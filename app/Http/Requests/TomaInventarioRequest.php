<?php

namespace App\Http\Requests;

use App\Local;
use App\Producto;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\TomaInventario\TomaInventario;

class TomaInventarioRequest extends FormRequest
{
  public $items_data = [];
  public $isStore;

  public function prepareForValidation()
  {
    $this->isStore = $this->route()->getActionMethod() != "update";
  }


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
    $validate_prefix = 'required_if:InvEsta,' . TomaInventario::ESTADO_CERRADO;

    $rules = [
      "InvFech" => "required|date",
      "InvNomb" => "required|max:120",
      "InvObse" => 'sometimes|nullable|max:120',
      "InvEsta" => sprintf('required|in:%s,%s',TomaInventario::ESTADO_CERRADO,TomaInventario::ESTADO_PENDIENTE),
      'items' => $validate_prefix,
      "items.*.Id" => $validate_prefix,
      "items.*.ProCodi" => $validate_prefix,
      "items.*.proNomb" => $validate_prefix . "|max:120",
      // "items.*.proMarc" => $validate_prefix . "|max:120",
      "items.*.UnpCodi" => $validate_prefix ,
      "items.*.ProStock" => $validate_prefix ,
      "items.*.ProInve" => $validate_prefix . "|max:120",
      "items.*.ProPUCS" => $validate_prefix . "|numeric|min:0",
      "items.*.ProPUVS" => $validate_prefix . "|numeric|min:0",       
    ];

    if ($this->isStore)  {
      $rules["LocCodi"] = 'required';
    }

    if ( $this->InvEsta == TomaInventario::ESTADO_CERRADO ) {
      // $str_rules_items = $this->isStore ?
      // $this->InvEsta == TomaInventario::ESTADO_PENDIENTE
      // $validate_prefix = 'required_if:InvEsta,' . TomaInventario::ESTADO_CERRADO;
      // $rules['items'] = $validate_prefix;
      // $rules["items.*.Id"] = $validate_prefix;
      // $rules["items.*.ProCodi"] = $validate_prefix;
      // $rules["items.*.proNomb"] = $validate_prefix . "|max:120";
      // $rules["items.*.proMarc"] = $validate_prefix . "|max:120";
      // $rules["items.*.UnpCodi"] = $validate_prefix ;
      // $rules["items.*.ProStock"] = $validate_prefix ;
      // $rules["items.*.ProInve"] = $validate_prefix . "|max:120";
      // $rules["items.*.ProPUCS"] = $validate_prefix . "|numeric|min:0";
      // $rules["items.*.ProPUVS"] = $validate_prefix . "|numeric|min:0"; 
      /////

      // $rules['items'] = 'required';
      // $rules["items.*.Id"] = "required";
      // $rules["items.*.ProCodi"] = "required";
      // $rules["items.*.proNomb"] = "required|max:120";
      // $rules["items.*.proMarc"] = "required|max:120";
      // $rules["items.*.UnpCodi"] = "required";
      // $rules["items.*.ProStock"] = "required";
      // $rules["items.*.ProInve"] = "required|numeric|min:0";
      // $rules["items.*.ProPUCS"] = "required|numeric|min:0";
      // $rules["items.*.ProPUVS"] = "required|numeric|min:0"; 
    }

    return $rules;
  }

  public function validateItems(&$validator)
  {
    if( ! $this->items ){
      return true;
    }

    for( $i = 0; $i < count($this->items) ; $i++ ){

      $item = $this->items[$i];
      
      $producto = Producto::find($item['Id']);
      
      if( is_null($producto) ){
        $validator->errors()->add('Id', "El Producto Ingresado en la linea {$i} no existe");
        return false;
      }

      $stock = $item['ProStock'];

      $costo = $producto->ProPUCS;
      $inventario = $item['ProInve'];
      $importe = $costo * $inventario;
      $is_ingreso = $inventario > $stock;

      $this->items_data[] = [
        "Id" => $item['Id'],
        "ProCodi"  => $producto->ProCodi,
        "proNomb"  => $producto->ProNomb,
        "proMarc"  => $item['proMarc'],
        "UnpCodi"  => $producto->unpcodi,
        "ProStock" => $stock,
        "ProInve"  => $inventario,
        "ProPUCS"  => $costo,
        "ProPUVS"  => $importe,
        "is_ingreso"  => $is_ingreso,
      ];
     }

     return true;
  }


  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        // Validar Local
        if( $this->isStore  ){
          if ( !Local::find($this->LocCodi)  ) {
            $validator->errors()->add('LocCodi', 'El Local Ingresado no existe');
            return;
          }

          if(auth()->user()->locales->where('loccodi', $this->LocCodi)->count() == 0){
            $validator->errors()->add('LocCodi', 'No puedes registrar una toma de inventario para este local. Ya que no esta asociado con tu usuario');
            return;
          }
        }

        else {
          $tomaInventario = TomaInventario::findOrfail($this->route()->parameters()['id']);
          
          if($tomaInventario->IsCerrada()){
            $validator->errors()->add('toma_inventario', 'Esta Toma Inventario ya no se puede modificar porque ya esta cerrada');
            return;
          }

        }

        
        // Validar que no exista otro inventario pendiente
        if (get_empresa()->hasTomaInventarioPendiente($this->LocCodi)) {
          $validator->errors()->add('toma_inventario', 'No puede registrar una nueva toma de inventario, mientras tenga una pendiente');
          return;
        }

        // Validar Items
        if( $this->validateItems($validator) ){
          return;
        }
      });
    }
  }

  public function messages()
  {
    return [
      "LocCodi.required" => 'El local es requerido',
      "InvFech.required" => "La fecha es requerida",
      "InvNomb.required" => "El Nombre es requerido",
      "InvObse.max" => "La observación no puede ser mas de :max caracteres",
    ];
  }
}
