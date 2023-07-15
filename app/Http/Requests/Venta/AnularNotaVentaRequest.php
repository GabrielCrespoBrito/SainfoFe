<?php

namespace App\Http\Requests\Venta;

use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class AnularNotaVentaRequest extends FormRequest
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
    return [];
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {
        $venta = Venta::findOrfail($this->route()->parameters['id']);
        
        if (!$venta->isNotaVenta()) {
          $validator->errors()->add('field', 'Solo se puede anular nota de venta');
          return;
        }

        if ($venta->isAnulada()) {
          $validator->errors()->add('field', 'Este documento ya se encuentra anulado');
          return;
        }




      });
    }
  }
}
