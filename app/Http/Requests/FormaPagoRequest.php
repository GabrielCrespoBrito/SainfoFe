<?php

namespace App\Http\Requests;

use App\FormaPago;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class FormaPagoRequest extends FormRequest
{
  public $isStore;
  public $isCredito;


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
    $this->isStore = $this->route()->getName() == "formas-pago.store";
    $this->isCredito = $this->contipo == FormaPago::TIPO_DIFERIDO;

    $this->merge([
      'connomb' => strtoupper($this->connomb),
    ]);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $rules = [
      'connomb' => 'required|max:100',
      'contipo' => 'required|in:C,D',
    ];

    if ($this->isCredito) {
      $rules['items'] = 'required';
      $rules['items.*.PgoDias'] = 'required|integer|min:0';
    }

    return $rules;
  }


  public function validateItems(&$validator)
  {
    if ($this->isCredito) {
      for ($i = 0, $oldValue = 0; $i < count($this->items); $i++) {
        $currentValue  = $this->items[$i]['PgoDias'];
        if ($i === 0) {
          $oldValue = $currentValue;
          continue;
        } else {
          if ($oldValue >= $currentValue) {
            $validator->errors()->add('PgoCodi', 'Los valores tienen que ser continuos. Ej. (10-15-30)');
            return;
          }
          $oldValue = $currentValue;
        }
      }
    }
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $validator->after(function ($validator) {


        $isStore = $this->route()->getName() == 'formas-pago.store';
        $fp = FormaPago::where('connomb', $this->connomb)->first();

        # Creando
        if ($isStore) {
          if ($fp) {
            $validator->errors()->add('connomb', 'Ya hay una forma de pago con el mismo nombre');
            return;
          }
          $this->validateItems($validator);
        }

        # Modificando
        else {
          $id_current = $this->route()->parameters['formas_pago'];
          $currentFP = FormaPago::findOrfail($id_current);

          if ($currentFP->isSystem()) {
            noti()->error('No permitida', 'Esta forma de pago no se puede modificar');
            return redirect()->route('formas-pago.index');
          }


          if ($fp && optional($fp)->id != $currentFP->id) {
            $validator->errors()->add('connomb', 'Ya hay una forma de pago con el mismo nombre');
            return;
          }
          $this->validateItems($validator);
        }
      });
    }
  }

  public function messages()
  {
    return [
      'items.required' => 'Cuando la forma de pago es por credito, es necesaria al menos un(1) plazo'
    ];
  }
}
