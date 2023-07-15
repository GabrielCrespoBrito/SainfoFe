<?php

namespace App\Http\Requests;

use App\User;
use App\UserEmpresa;
use Illuminate\Foundation\Http\FormRequest;

class UserEmpresaDeleteRequest extends FormRequest
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
    $validator->after( function($validator){
      $user_empresa = UserEmpresa::find($this->route()->parameters['id']);
      if( $user_empresa->documentos->where('usucodi' , $user_empresa->usucodi )->count()){
        $validator->errors()->add('usucodi', 'Este usuario esta tiene series asociadas, tiene que eliminar esas series primero');
      }
    });

  }
}
