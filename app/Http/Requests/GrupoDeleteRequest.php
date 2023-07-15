<?php

namespace App\Http\Requests;

use App\Familia;
use App\Grupo;
use App\Producto;
use Illuminate\Foundation\Http\FormRequest;

class GrupoDeleteRequest extends FormRequest
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
			'id' => 'required',
		];
	}

	public function withValidator($validator)
	{
		if( !$validator->fails() ){
			
			$validator->after(function ($validator){

				$id = $this->id;
				// $grupos = Grupo::where('empcodi', empcodi())->get();
				$grupo_current = Grupo::find($id);

				if( is_null($grupo_current) ){
					$validator->errors()->add('MarCodi', 'El codigo del grupo es incorrecto' );
				}

				else {

					$error = false;

			    if (
						Familia::where('gruCodi' , $id) 
						->where('empcodi' , $grupo_current->empcodi )
						->count()){
			    	$error = true;
			      $message_error = "No se puede borrar este grupo por que contiene una familia asociada";
					}

					if(
						Producto::where('grucodi' , $id)
						->where('empcodi' , empcodi())
						->count()){

			      $error = true;      
			      $message_error = "No se puede borrar este grupo, por que hay productos asociados";
			    }

			    if($error){
						$validator->errors()->add('gruCodi', $message_error );
			    }

				}
				// else

			});    
		
		}
	}
	
}
