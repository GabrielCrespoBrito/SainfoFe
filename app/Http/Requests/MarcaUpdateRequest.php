<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Marca;

class MarcaUpdateRequest extends FormRequest
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
			'MarNomb' => 'required|max:50',	
        ];
    }
    
	public function withValidator($validator)
	{
		$validator->after(function ($validator){

            $marcas = Marca::where('empcodi', empcodi())->get();
            $marca_current = $marcas->where('MarCodi', $this->MarCodi)->first();

			if( is_null($marca_current) ){
			    $validator->errors()->add('MarCodi', 'El codigo de la marca es incorrecto' );
            }

			else {
                $marca_withName = $marcas->where('MarNomb', $this->MarNomb)->first();

			    if( !is_null($marca_withName)  ){
                    if( $marca_withName->MarCodi !== $marca_current->MarCodi ){
                        $validator->errors()->add('MarNomb', 'El nombre (' .  $this->MarNomb .  ') esta repetido' );
                    }

                }
            }

		});         

	}
    
}
