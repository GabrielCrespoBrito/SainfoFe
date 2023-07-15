<?php

namespace App\Http\Requests\Landing;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
      $this->isStore = $this->route()->getName() == 'admin.pagina.banners.store';

      $rules = [];
      
      if($this->isStore){
        $rules['nombre'] = 'required|unique:pagina_banners,nombre';
        $rules['imagen'] = 'required|mimes:jpg,jpeg,png,gif,webp';
      }

      else {
        $id = $this->route()->parameters['id'];
        $rules['nombre'] = "required|unique:pagina_banners,nombre,{$id}";
        $rules['imagen'] = $this->imagen ? 'required|mimes:jpg,jpeg,png,gif,webp' : 'sometimes|nullable';
      }

      return $rules;
    }
}
