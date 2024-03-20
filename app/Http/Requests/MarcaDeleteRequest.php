<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Marca;
use App\Producto;


class MarcaDeleteRequest extends FormRequest
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
            'MarCodi' => 'required',
        ];
    }

    
    public function withValidator($validator)
    {

        if( !$validator->fails() ) {
        

            $validator->after(function ($validator){

                $marca_current = Marca::find($this->MarCodi);

                if( is_null($marca_current) ){
                    $validator->errors()->add('MarCodi', 'El codigo de la marca es incorrecto' );
                }

                else {
                    $productos_with_marca = Producto::where('marcodi', $this->MarCodi)->count();  
                    
                    if( $productos_with_marca ){
                      
                        $validator->errors()->add('field', "No puede borrar esta marca, por que esta siendo usada en algun producto" );     
                    }
                }

            });         
        
        }

    }


}
