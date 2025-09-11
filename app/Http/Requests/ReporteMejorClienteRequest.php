<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ReporteMejorClienteRequest extends FormRequest
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
      'fecha_desde' => 'required|date',
      'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
      'local' => 'required',
      'tipo_reporte' => 'required|in:pdf,excell',
    ];
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {
        $fecha_desde_carbon = new Carbon($this->fecha_desde);
        $fecha_hasta_carbon = new Carbon($this->fecha_hasta);
        $dias = config('app.reporte_mejores_clientes_dias_limite');
        if ($fecha_desde_carbon->addDays($dias)->isBefore($fecha_hasta_carbon)) {
          $validator->errors('fecha_hasta', "El lapso del reporte no puede superar los {$dias} dias");
        }
      });
    }
  }
}
