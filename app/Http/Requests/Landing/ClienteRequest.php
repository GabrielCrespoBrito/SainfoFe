<?php

namespace App\Http\Requests\Landing;

use App\Rules\RucValidation;
use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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

  public function rules()
  {
    
    $isStore = $this->route()->getName() == 'admin.pagina.clientes.store';
    
    if( $isStore ){
      return [
        'razon_social' => 'required|unique:pagina_clientes,razon_social',
        'sitio' => 'sometimes|nullable|url',
        'ruc' => ['required',  new RucValidation(), 'unique:pagina_clientes,razon_social'],
        'image' => 'required|mimes:jpeg,png,gif',
        'active' => 'required|in:0,1',
      ];
    }

    $id = $this->route()->parameters()['id'];

    return [
      'razon_social' => "required|unique:pagina_clientes,razon_social,{$id}",
      'sitio' => 'sometimes|nullable|url',
      'ruc' => ["required", new RucValidation(), "unique:pagina_clientes,razon_social,{$id}" ],
      'image' => $this->image ? 'mimes:jpeg,png,gif' : 'sometimes|nullable',
      'active' => 'required|in:0,1',
    ];
  }
}
