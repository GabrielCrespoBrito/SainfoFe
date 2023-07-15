<?php

namespace App\Http\Requests;

use App\Cotizacion;
use Illuminate\Foundation\Http\FormRequest;

class CotizacionAnulacionRequest extends FormRequest
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
      //
    ];
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {
        $cotizacion = Cotizacion::findOrfail($this->route()->parameters()['id']);

        if ($cotizacion->isAnulado()) {
          $validator->errors()->add('cotizacion', 'Ya se encuentra anulado este documento');
          return back();
        }

        if ($cotizacion->isFacturado()) {
          $validator->errors()->add('cotizacion', 'No se puede Anular porque ya esta Facturado');
          return back();
        }
      });
    }
  }
}
