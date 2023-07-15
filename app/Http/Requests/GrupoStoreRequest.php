<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Grupo;

class GrupoStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
		return  [
			'GruCodi' => 'required|digits_between:1,3',
			'GruNomb' => 'required|max:50',			
		];
    }

	public function withValidator($validator)
	{
		$validator->after(function ($validator){

            $grupos = Grupo::where('empcodi', empcodi())->get();

			if( $grupos->where('GruCodi', $this->GruCodi)->count() ){
			    $validator->errors()->add('GruCodi', 'El codigo (' . $this->GruCodi . ') esta repetido' );
            }
			else {
                $nombre = strtoupper($this->GruNomb);
			    if( $grupos->where('GruNomb', $nombre)->count() ){
                    $validator->errors()->add('GruNomb', 'El nombre (' .  $nombre .  ') esta repetido' );
                }
            }

		});         

    }
    
    public function messages(){
        return [
            'GruCodi.required' => 'El Codigo del grupo es obligatorio',
            'GruCodi.numeric' => 'El Codigo del grupo tiene que ser numerico',
            'GruCodi.max' => 'El Codigo solo puede tener 3 cifras',
			'GruNomb.max' => 'El nombre del grupo no puede tener mas de 50 caracteres',
        ];
    }


}
