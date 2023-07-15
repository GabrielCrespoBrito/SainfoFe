<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserDeleteRequest extends FormRequest
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
      ];
  }

  public function withValidator($validator)
  {
    $validator->after( function($validator){

      $id = $this->route()->parameters['id'];
      $user = User::find($id);
      
      if (! $user ) {
        $validator->errors()->add('usucodi', 'No existe el usuario suministrado');
        return;
      }

      if ( $user->isAdmin() ) {
        $validator->errors()->add('usucodi', 'No puede eliminarse este usuario');
        return;
      }

      if ($user->isOwner()) {
        $validator->errors()->add('usucodi', 'No puede eliminarse el usuario principal');
        return;
      }

      $register = $user->hasRegistros();


      if( $register->success ){
        $validator->errors()->add('usucodi', "Este usuario tiene registros de {$register->modelo}");
        return;
      }

    });

  }
}
