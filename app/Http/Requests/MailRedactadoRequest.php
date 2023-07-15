<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailRedactadoRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
     'hasta' => 'required|max:144',
     'asunto' => 'nullable|max:44',
     'mensaje' => 'sometimes|max:145',
     'documentos' => 'nullable',
     'corre_hasta' => 'nullable',     
    ];
  }

  public function withValidator($validator)
  {
    if( !$validator->fails() )  {

      $validator->after(function($validator){

        $emails = explode( "," , $this->hasta);

        if( count( $emails ) > 3 ){
          $validator->errors()->add('corre_hasta', ' El limite de correos a enviar son 3');
        }

        foreach( $emails as $mail ){
          if( !is_valid_email($mail) ){
            $validator->errors()->add('hasta','Tiene que introducir direcciónes de correos validas');
          }
        }

      });

      
    }
  }
}
