<?php

namespace App\Http\Requests\Monitoreo;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaCreateRequest extends FormRequest
{
  /**
   * @param bool
   */
  public $isPost;

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
   * Obtener la validación del certificado dependiendo del tipo de petición post/put
   * @param string $key 
   * 
   * @return string 
   */
  public function getCertValidation($ext)
  {
    return $this->isPost ? "nullable|sometimes|" : "sometimes|";
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */


  public function rules()
  {
    $this->isPost = $this->getMethod() == "POST";

    $rules = [
      # ----------------------------
      'razon_social' => 'required',
      'ruc' => 'required|unique:monitor_empresas,ruc|digits:11',
      'usuario_sol' => 'required',
      'clave_sol' => 'required',

      'email' => 'nullable|email',
      'telefono' => 'nullable',
      # ----------------------------      
      'cert_key' => $this->getCertValidation('key'),
      'cert_cer' => $this->getCertValidation('cer'),
      'cert_pfx' => $this->getCertValidation('pfx'),
      # ----------------------------
      'id.*' => 'nullable',
      'tipo_documento.*' => 'required',
      'serie.*' => 'required|max:4',
    ];

    if( $this->isPost ){
      $rules['ruc'] = 'required|unique:monitor_empresas,ruc|digits:11';
    }

    else {
      $id = $this->route()->parameters()['empresa'];
      $rules['ruc'] = 'required|digits:11|unique:monitor_empresas,ruc,'. $id;
    }


    return $rules;
  }

  public function withValidator($validator)
  {
    if( ! $validator->fails() ){

      
      $validator->after(function ($validator) {
        
        if ($this->cert_key) {
          if ($this->cert_key->getClientOriginalExtension() != 'key') {
            $validator->errors()->add('cert_key','La extension del archivo tiene que ser .key');
          }
        }
        
        if ($this->cert_cer) {
          if ($this->cert_cer->getClientOriginalExtension() != 'cer') {
            $validator->errors()->add('cert_cer','La extension del archivo tiene que ser .cer');
          }
        }
        
        if ($this->cert_pfx) {
          if ($this->cert_pfx->getClientOriginalExtension() != 'pfx') {
            $validator->errors()->add('cert_pfx','La extension del archivo tiene que ser .pfx');
          }
        }
        
    });

    }
  }
}