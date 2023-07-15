<?php

namespace App\Http\Requests\Banco;

use App\BancoEmpresa;
use App\Caja;
use Illuminate\Foundation\Http\FormRequest;

class BancoAperturaRequest extends FormRequest
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
            'periodo_id' => 'required|exists:mes,mescodi',
            'cuenta_id' => 'required',
        ];
    }

    public function withValidator( $validator )
    {

        if( ! $validator->fails() ){
            
            $validator->after(function($validator){

                if(is_null(BancoEmpresa::find( $this->cuenta_id ))){
                    $validator->errors()->add('cuenta', 'El codigo de la cuenta no existe');
                }

                if ( Caja::mesCodi($this->periodo_id)->cueCodi($this->cuenta_id)->first() ) {
                    $validator->errors()->add('cuenta', 'Esta cuenta ya esta aperturada en el mes suministrado');
                }

            });

        }

    }

}
