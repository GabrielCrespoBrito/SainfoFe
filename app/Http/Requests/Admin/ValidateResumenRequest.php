<?php

namespace App\Http\Requests\Admin;

use App\Resumen;
use Illuminate\Foundation\Http\FormRequest;

class ValidateResumenRequest extends FormRequest
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

    if ($this->tipo_validacion != "directo") {
      empresa_bd_tenant($this->empresa_id);
      $validator->after(function ($validator) {
        $numoper = $this->route()->parameters()['numoper'];
        $docnume = $this->route()->parameters()['docnume'];
        $resumen = Resumen::findMultiple($numoper, $docnume);
        if ($resumen->isResumenReal()) {
          $this->validator->errors()->add('docnume', 'Los Resumenes de envio, solo se pueden su validar con su ticket, con la Opción "Validar Ticket" , o con la opción de "Directa"');;
          return;
        }
      });
    }
  }
}
