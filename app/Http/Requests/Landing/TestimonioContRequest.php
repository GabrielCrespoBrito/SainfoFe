<?php

namespace App\Http\Requests\Landing;

use Illuminate\Foundation\Http\FormRequest;

class TestimonioContRequest extends FormRequest
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
      $this->isStore = $this->route()->getName() == 'admin.pagina.contabilidad-testi.store';
      $rules = [
        'representante' => 'required|max:245',
        'cargo' => 'required|max:245',
        'testimonio_text' => 'sometimes|max:500',
      ];
      
      if($this->isStore){
        $rules['imagen'] = 'required|mimes:jpeg,png,PNG,gif';
      }

      else {
      $rules['imagen'] = $this->imagen ? 'required|mimes:jpeg,png,gif' : 'sometimes|nullable';
      }

      return $rules;
    }
}
