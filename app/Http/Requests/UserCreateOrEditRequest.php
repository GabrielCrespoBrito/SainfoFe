<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateOrEditRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {     
    $rules = [
      'codigo'    => 'required|numeric|digits:2|unique:usuarios,usucodi',
      'cargo'     => 'required|numeric',
      'usuario'   => 'required|alpha|unique:usuarios,usulogi,'.$this->codigo,
      'nombre'    => 'required|string',
      'password'  => 'required|string|min:4|confirmed',            
      'telefono'  => 'nullable|numeric',          
      'direccion' => 'nullable',   
    ];

    return $rules;

  }  
}
