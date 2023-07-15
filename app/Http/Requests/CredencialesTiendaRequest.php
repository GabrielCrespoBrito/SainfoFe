<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CredencialesTiendaRequest extends FormRequest
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
      'woocomerce_api_url' => 'required|url',
      'woocomerce_client' => 'required',
      'woocomerce_client_key' => 'required',
    ];
  }
}