<?php

namespace App\Http\Requests;

use App\SerieDocumento;
use Illuminate\Foundation\Http\FormRequest;

class UserDocumentoDeleteRequest extends FormRequest
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

      $serie_documento = SerieDocumento::find($this->route()->parameters['id']);
      // if( $validator->valid() ){
        // $validator->errors()->add( 'field' , 'message' );
      // }
    });
  }
}
