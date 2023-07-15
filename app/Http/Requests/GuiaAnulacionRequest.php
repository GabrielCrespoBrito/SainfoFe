<?php

namespace App\Http\Requests;

use App\GuiaSalida;
use Illuminate\Foundation\Http\FormRequest;

class GuiaAnulacionRequest extends FormRequest
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
    return [];
  }

  public function withValidator($validator)
  {
    if( ! $validator->fails() ){

      $validator->after(function($validator){
           
        $guia_id = $this->route()->parameters['guia_id'];

        $guia = GuiaSalida::find( $guia_id );

        if( is_null($guia) ){
          $validator->errors()->add('guia' , 'Este guia no existe no existe');
          return;
        }

        if ($guia->isAnulada()) {
          $validator->errors()->add('guia', 'Esta guia ya esta anulada');
          return;
        }

        if ( $guia->hasFormato() ) {
          if (! $guia->isSendSunat() ) {
            $validator->errors()->add('guia', 'Esta guia no ha sido enviada a la sunat');
            return;
          }
        }
      });

    }
  }

}
