<?php

namespace App\Http\Requests;

use App\Helpers\DocumentHelper;
use App\TipoDocumento;
use App\TipoDocumentoPago;
use Illuminate\Foundation\Http\FormRequest;

class VendedorCoberturaReportRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
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

    $rules = [
      "vendedor_id" => "nullable",
      "local_id" => "nullable", "003",
      "fecha_desde" => "required|date",
      "fecha_hasta" => ['required', 'date', 'after_or_equal:' . $this->fecha_desde],
      "marca_id" => ['nullable',  'sometimes'],
      "cliente_id" => "nullable|", "00008",
      "tipo_reporte" => "required|in:0,1",
      "tipo_documento" => "nullable|sometimes|in:" . implode(",", TipoDocumentoPago::VALID_DOCUMENTOS),
    ];
    return $rules;
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
