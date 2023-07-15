<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteExistsRequest extends FormRequest
{
  public function authorize()
  {
  	return true;
  }

  public function rules()
  {
		return [
			'id_cliente' => 'required'
		];
  }
}
