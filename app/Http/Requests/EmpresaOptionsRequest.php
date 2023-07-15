<?php

namespace App\Http\Requests;

use App\EmpresaOpcion;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaOptionsRequest extends FormRequest
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
    $rules = [];

    $settings = new EmpresaOpcion();

    foreach ($this->all() as $field => $value) {
      $info_field =  $settings->getInfoSetting($field);
      
      if( $info_field  ){
        $rules[$field] = $info_field['rules_validation'];
      } 
    }

    return $rules;
  }


}
