<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserEmpresaCreateRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'id_empresa' => 'required',
      'id_user' => 'required',      
    ];
  }

  public function withValidator($validator)
  {
    $validator->after(function($validator){

      if( User::find($this->id_user)->empresas->where('empcodi' , $this->id_empresa)->count() ){
        $validator->errors()->add('id_empresa', 'Este usuario ya esta asociado a esta empresa');
      }

    });

  }


}
