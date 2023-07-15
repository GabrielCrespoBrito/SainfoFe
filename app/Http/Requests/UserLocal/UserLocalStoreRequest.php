<?php

namespace App\Http\Requests\UserLocal;

use App\Empresa;
use App\Models\UserLocal\UserLocal;
use Illuminate\Foundation\Http\FormRequest;

class UserLocalStoreRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */

	public $isStore;

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
		$this->isStore = strtolower($this->getMethod()) == "post";

		$rules = [
			'loccodi' => 'required',
		];

		if( $this->isStore ){
			$rules['usucodi'] = 'required';
		}

		return $rules;
	}

	public function withValidator($validator)
	{
		if( ! $validator->fails() ){

			$validator->after(function($validator){
        
				$empresa = Empresa::find( $this->empresa_id );
        empresa_bd_tenant($empresa->id());
				$users = $empresa->users;
				$locales = $empresa->locales;
				$users_locales = UserLocal::all();

				if( ! $this->isStore ){
					$usucodi = $this->route()->parameters['usucodi'];
					$loccodi = $this->route()->parameters['loccodi'];
					$userlocal = UserLocal::find( $usucodi , $loccodi );
				}


				if( ! $locales->where('LocCodi', $this->loccodi )->count() ){
					$validator->errors()->add('loccodi', 'No existe este local');
					return;
				}

				if( ! $users->where('usucodi', $this->usucodi )->count() ){
					$validator->errors()->add('usucodi', 'No existe este usuario');					
					return;
				}

				if( $this->isStore ){
					if ( $users_locales->where('loccodi', $this->loccodi)->where('usucodi', $this->usucodi)->count() ){
						$validator->errors()->add('usucodi', 'El usuario ya esta asociado a el local seleccionado');										
					}
				}
				else {
				}

			});

		}
	}
}
