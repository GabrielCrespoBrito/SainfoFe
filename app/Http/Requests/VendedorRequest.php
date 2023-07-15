<?php

namespace App\Http\Requests;

use App\Vendedor;
use App\UserEmpresa;
use Illuminate\Foundation\Http\FormRequest;

class VendedorRequest extends FormRequest
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
      "vennomb" => ['required', 'regex:/^[a-zA-Z0-9\s]+$/', 'max:65'],
      "venmail" => 'nullable|sometimes|email|max:45',
      "ventel1" => 'nullable|sometimes|max:45',
      'vendire' => 'nullable|sometimes|max:100',
      'usucodi' => 'nullable'
    ];
  }


  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        if ($this->usucodi == null) {
          return;
        }
        
        if ( !get_empresa()->users->where('usucodi', $this->usucodi)->first()  ) {
          $validator->errors()->add('usulogi', 'El Usuario no existe o no esta asociado a esta empresa');
          return;
        }

        $isStore = $this->route()->getName() == 'vendedor.store';
        
        
        if( $isStore ){
          if(Vendedor::where('usucodi', $this->usucodi)->first()){
            $validator->errors()->add('usulogi', 'El Usuario no existe o no esta asociado a esta empresa');
            return; 
          }
        }

        else {

          $id = $this->route()->parameters()['vendedore'];
          $vendedor = Vendedor::findOrfail($id);
          $vendedorCurrent = Vendedor::where('usucodi', $this->usucodi)->first();

          if( $vendedorCurrent == null ){
            return;
          }

          if ( $vendedor->Vencodi != $vendedorCurrent->Vencodi ) {
            $validator->errors()->add('usulogi', 'El Usuario ya esta asociado a otro vendedor');
            return;
          }
        }

      });
    }
  }
}
