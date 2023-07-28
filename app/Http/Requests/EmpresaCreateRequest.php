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
    // _dd( $this->all() );
    // exit();
    
    $rules = [
      // Razon social
    // 'nombre_empresa' => 'required|max:150|unique:opciones,EmpNomb',
    // Ruc
    'ruc'=> 'required|numeric|digits:11|unique:opciones,EmpLin1',
    'direccion'         => 'required|max:120',    
    // 'email'             => 'nullable|email|max:120||unique:opciones,EmpLin3',    
    'telefonos'         => 'nullable|max:100',
    // 'nombre_comercial' => 'required|max:150|unique:opciones,EmpLin5',
    'ubigeo'            => 'required|numeric|digits:6',
    'departamento'      => 'required|max:45',
    'provincia'         => 'required|max:45',
    'distrito'          => 'required|max:45',
    'rubro'             => 'nullable|max:200',
    // 'tipo' => 'sometimes|in:web,escritorio',
  ];
  
  if( $this->tipo == "web" ){
    $rules['clave_firma'] = 'required|max:45';
    $rules['usuario_sol'] = 'required|max:45';
    $rules['clave_sol'] = 'required|max:45';
    $rules['fe_servicio'] = 'required';
    $rules['fe_ambiente'] = 'required';
    $rules['formato_hoja'] = 'required';
    $rules['logo_principal'] = 'sometimes|nullable|mimes:jpeg,bmp,png,gif|max:2048';
    $rules['logo_secundario'] = 'sometimes|nullable|mimes:jpeg,bmp,png,gif|max:2048';
    }
    return $rules;
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
