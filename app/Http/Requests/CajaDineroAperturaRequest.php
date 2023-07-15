<?php

namespace App\Http\Requests;

use App\Caja;
use Illuminate\Foundation\Http\FormRequest;

class CajaDineroAperturaRequest extends FormRequest
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
			'CANINGS' => 'required|numeric|min:0',
			'CANINGD' => 'required|numeric|min:0',
		];
	}

	public function message(){
		return [
		  'CANINGS.numeric' => 'El ingreso en soles tiene que ser numerico',
		  'CANINGD.numeric' => 'El ingreso en dolares tiene que ser numerico',  
		  'CANINGS.min'     => 'El ingreso en soles tiene que ser mayor que :min',
		  'CANINGD.min'     => 'El ingreso en dolares tiene que ser mayor que :min',  
		];
	}

  public function withValidator($validator)
  {    
    $validator->after(function ($validator){

    	$caja = Caja::find($this->id_caja);

      	if( $caja->isCerrada() ){
      		$validator->errors()->add('caja' , 'Esta caja esta cerrada');
      	}

    });
	}
}


