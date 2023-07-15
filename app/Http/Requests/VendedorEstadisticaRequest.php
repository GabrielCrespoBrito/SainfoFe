<?php

namespace App\Http\Requests;

use App\Helpers\DocumentHelper;
use Illuminate\Foundation\Http\FormRequest;

class VendedorEstadisticaRequest extends FormRequest
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
      "fecha_desde" => "required|date",
      "fecha_hasta" => ['required', 'date', 'after_or_equal:' . $this->fecha_desde],
    ];
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {
        $dias = config('app.reporte_vendedor_dias_limite');
        if ((new DocumentHelper)->sobrePasaDias($this->fecha_desde, $this->fecha_hasta, $dias)) {
          $validator->errors()->add('fecha_hasta', 'El Limite del Reporte es ' . $dias . ' dias');
          return;
        }
      });
    }
  }
}
