<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBasicRequest extends FormRequest
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
      'direccion'         => 'required|max:250',
      'ubigeo'            => 'required|exists:ubigeo,ubicodi',
      'email'             => 'required|max:120',
      'telefonos'         => 'required|max:100',
      'rubro'             => 'nullable|sometimes|max:200',
    ];
  }
}
