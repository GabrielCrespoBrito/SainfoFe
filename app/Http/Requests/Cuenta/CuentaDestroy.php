<?php

namespace App\Http\Requests\Cuenta;

use App\BancoEmpresa;
use Illuminate\Foundation\Http\FormRequest;

class CuentaDestroy extends FormRequest
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
    	$validator->after(function($validator){
        $cuentaCurrent = BancoEmpresa::findOrfail( $this->route()->parameters()['cuentum']);
        if( $cuentaCurrent->cajas->count() ){
          $validator->errors()->add('CueNume', 'La cuenta tiene cajas asociadas');
        }
		});
	}
}