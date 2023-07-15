<?php

namespace App\Http\Requests\Admin;

use App\TipoPago;
use Illuminate\Foundation\Http\FormRequest;

class TipoPagoRequest extends FormRequest
{
  public $isStore;

  const NOMBRE_REPETIDO = "El Nombre del Tipo de Pago esta repetido";

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  public function prepareForValidation()
  {
    $this->isStore = $this->route()->getName() == "admin.tipo_pago.store";

    $this->merge(['TpgNomb' => strtoupper(trim($this->TpgNomb))]);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'TpgNomb' => $this->isStore ? 'required|unique:tipo_pago,TpgNomb' : 'required',
      'TdoBanc' => sprintf('required|in:%s,%s', TipoPago::NO_BANCARIO, TipoPago::IS_BANCARIO )
    ];
  }

  public function withValidator($validator)
  {
    if(!$validator->fails()){
      $validator->after(function($validator){
        if( !$this->isStore ){
          $tipo_pago = TipoPago::findOrfail($this->route()->parameters['id']);
          if( $tipo_pago !== $this->tpgNomb ){
            if(TipoPago::where('TpgNomb', $this->TpgNomb)->count()){
              $validator->errors()->add('TpgNomb', self::NOMBRE_REPETIDO );
            }
          }
        }
      });
    }
  }


  public function messages()
  {
    return [
      'TpgNomb.required' => 'El Nombre del Tipo de Pago es requerido',
      'TpgNomb.unique' => self::NOMBRE_REPETIDO
    ];
  }
}
