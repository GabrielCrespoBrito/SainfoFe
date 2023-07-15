<?php

namespace App\Http\Requests;

use App\Models\TomaInventario\TomaInventario;
use Illuminate\Foundation\Http\FormRequest;

class TomaInventarioImportExcellRequest extends FormRequest
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
      // 'estado' => sprintf('required|in:%s,%s', TomaInventario::ESTADO_CERRADO, TomaInventario::ESTADO_PENDIENTE),
      'excell' => sprintf('required|mimes:xlsx|max:1024'),
      'local' => 'required',
    ];
  }
}
