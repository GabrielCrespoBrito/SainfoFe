<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PerfilRequest extends FormRequest
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
		$usucodi = auth()->user()->usucodi;
		return [
			'usunomb' => 'required|max:145',
			'usutele' => 'required|max:45',
			'usudire' => 'required|max:145',
			'email' => ['required','max:200','email'],
		];
	}

	public function withValidator($validator)
	{
		if(!$validator->fails()){
			$validator->after(function($validator){
				
				$user = User::where('email', $this->email)->first();
				
				if( is_null($user)  ){
					return;
				}

				if( $user->usucodi != auth()->user()->usucodi ){
					$validator->errors()->add( 'email' , 'El email ya ha sido tomado' );
				}

			});
		}

	}

}
