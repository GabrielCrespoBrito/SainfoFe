<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmpresaRequest extends FormRequest
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

    public function prepareForValidation()
    {
      $this->logos_dimenciones = config('app.logos_dimenciones');
    }
  

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
          'ruc'               => 'required|numeric|digits:11',
          'direccion'         => 'required|max:250',
          'ubigeo'            => 'required|exists:ubigeo,ubicodi',
          // 'departamento'      => 'required|alpha|max:45',
          // 'provincia'         => 'required|alpha|max:45',
          // 'distrito'          => 'required|max:45',
          'email'             => 'required|max:120',
          'telefonos'         => 'required|max:100',
          'rubro'             => 'nullable|sometimes|max:200',
          'clave_firma'       => 'required|max:45',
          'usuario_sol'       => 'required|max:45',
          'cert_nomb'         => 'required|max:45',                        
          'clave_sol'         => 'required|max:45',            
          'formato_hoja'      => 'required',
          'url_consulta'      => 'required',

          "logo_secundario" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['ticket']['width']},max_height={$this->logos_dimenciones['ticket']['height']}",

          "logo_subtitulo" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['subtitulo']['width']},max_height={$this->logos_dimenciones['subtitulo']['height']}",

          "logo_marca_agua" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['marca_agua']['width']},max_height={$this->logos_dimenciones['marca_agua']['height']}",          
      ];
    }
}
