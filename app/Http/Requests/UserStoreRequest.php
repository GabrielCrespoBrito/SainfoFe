<?php

namespace App\Http\Requests;

use App\User;
use App\Local;
use App\Permission;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
      'usuario'   => 'required|alpha_num',
      'nombre'    => 'required|string|min:4',
      'password'  => 'required|string|min:4|confirmed',
      'email'     => 'required|email|min:4',
      'telefono'  => 'nullable|numeric',
      'direccion' => 'nullable',
    ];
  }

  public function validateLocales(&$validator)
  {
    if( $this->local ){
      foreach( $this->local as $local ){
        if(Local::find($local) == null){
          $validator->errors()->add('usuario', sprintf('El Local (%s) no existe', $local) );
        }
      }
    }
  }

  public function validatePermissions(&$validator)
  {
    if ($this->permisos) {
      foreach ($this->permisos as $permiso) {
        if (!Permission::where('group',  $permiso )->count()){
          $validator->errors()->add('permiso', sprintf('El Permiso (%s) no existe', $permiso));
        }
      }
    }
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {

      if (User::where('usulogi', $this->usuario)->count()) {
        $validator->errors()->add('usuario', 'El nombre del usuario es repetido');
      }

      if (User::where('email', $this->email)->count()) {
        $validator->errors()->add('usuario', 'El email ya esta siendo utilizado por otro usuario');
      }

      if ( $this->validateLocales($validator) ){
        return;
      }

      if ($this->validatePermissions($validator)) {
        return;
      }


    });

      

  }
}
