<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaCreateRequest extends FormRequest
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
    return [
      // Razon social
    // 'nombre_empresa' => 'required|max:150|unique:opciones,EmpNomb',
    // Ruc
    'ruc'=> 'required|numeric|digits:11|unique:opciones,EmpLin1',
    // Dirección
    'direccion'         => 'required|max:120',    
    // Email
    // 'email'             => 'nullable|email|max:120||unique:opciones,EmpLin3',    
    'telefonos'         => 'nullable|max:100',
    // Nombre Comercial social
    // 'nombre_comercial' => 'required|max:150|unique:opciones,EmpLin5',
    'ubigeo'            => 'required|numeric|digits:6',
    'departamento'      => 'required|alpha|max:45',
    'provincia'         => 'required|alpha|max:45',
    'distrito'          => 'required|max:45',
    'rubro'             => 'nullable|max:200',
    'clave_firma'       => 'required|max:45',
    'usuario_sol'       => 'required|max:45',
    'clave_sol'         => 'required|max:45',

    'fe_servicio'         => 'required',
    'fe_ambiente'         => 'required',

    'formato_hoja'      => 'required',
    'logo_principal'  => 'sometimes|nullable|mimes:jpeg,bmp,png,gif|max:2048',
    'logo_secundario' => 'sometimes|nullable|mimes:jpeg,bmp,png,gif|max:2048',
    ];
  }


  public function withValidator($validator)
  {
    $validator->after(function($validator){
      if( strlen($this->ruc) && hay_internet() ){

        // $verificador = new \Sunat\Sunat();
        // if( !$verificador->search($this->ruc)['success']  ){
        //   $validator->errors()->add('EmpLin1', "Este RUC no existe en la RENIEC por favor verifique");
        // }
        // return;
      }
    });
  }

  public function messages()
  {
    return [
      'nombre_comercial.required' => 'La razón social es obligatorio',
      'ruc.required' => 'La RUC es obligatorio',      
      'ruc.unique' => 'La RUC ya esta siendo usado',            
      'direccion.required' => 'La dirección es obligatorio',      
      'ubigeo.required' => 'El ubigeo es obligatorio',
      'email.required' => 'El email es obligatorio',      
      'telefonos.required' => 'El/Los telefonos es/son obligatorio/s',
      'nombre_comercial.required' => 'El nombre Comercial es obligatorio',            
    ];
  }

}
