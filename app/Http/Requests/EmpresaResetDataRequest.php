<?php

namespace App\Http\Requests;

use App\Empresa;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaResetDataRequest extends FormRequest
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
      if(!$validator->fails()){
        $validator->after(function($validator){

          $empresa = Empresa::find($this->route()->parameters()['id']);
        
          // if ($empresa->isPlanRegular() || $empresa->isProduction()) {
          if ( $empresa->isProduction() ) {
            $validator->errors()->add('empresa','Esta Empresa no puede eliminarse su información porque tiene un plan regular o esta en producción');
            return;
          }
        });
      }
    }
}
