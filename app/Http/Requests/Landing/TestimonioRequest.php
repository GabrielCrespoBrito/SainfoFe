<?php

namespace App\Http\Requests\Landing;

use Illuminate\Foundation\Http\FormRequest;

class TestimonioRequest extends FormRequest
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

    public $isStore;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      // $isStore = $this->
      $this->isStore = $this->route()->getName() == 'admin.pagina.testimonios.store';

      // $imagenRule = $isStore ? 'required|'

      $rules = [
        'cliente_id' => 'required|exists:pagina_clientes,id',
        'representante' => 'required|max:245',
        'cargo' => 'required|max:245',
      'testimonio_text' => 'sometimes|max:245',
      'url_video' => 'sometimes|nullable|url',
      ];
      
      if($this->isStore){
        $rules['imagen'] = 'required|mimes:jpeg,png,gif';
      }


      else {
      $rules['imagen'] = $this->imagen ? 'required|mimes:jpeg,png,gif' : 'sometimes|nullable';
      }


      return $rules;
    }

    public function withValidator($validator)
    {
      if(!$validator->fails()){
        $validator->after(function($validator){

          if( $this->url_video == null && $this->testimonio_text == null ){
            $validator->errors()->add('testimonio_text', 'Tiene que registra un textmonio en texto o el link del video');
          }
          
        });
      }
    }

}
