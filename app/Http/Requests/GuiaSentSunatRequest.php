<?php

namespace App\Http\Requests;

use App\GuiaSalida;
use Illuminate\Foundation\Http\FormRequest;

class GuiaSentSunatRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
		];
	}

	public function withValidator($validator){

		$validator->after(function($validator){

			$id_guia = $this->route()->parameters()['id'];

			$guia = GuiaSalida::findOrfail($id_guia);

			if( $guia->fe_rpta == "0" ){
				$validator->errors()->add('guia' , 'Esta guia ya ha sido enviada');
        return;
			}

      if ($guia->pendiente()) {
        $link = route('guia.edit', ['id' =>  $guia->GuiOper, 'despachar' => true]);
        $validator->errors()->add('guia', sprintf('Tiene que ingresar los datos de <a target="_blank" href="%s">DESPACHO</a> de esta guia', $link));
        return;
      }

		});
	}
}
