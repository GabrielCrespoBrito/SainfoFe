<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaUpdateVisualizacionRequest extends FormRequest
{
  public $logos_dimenciones;
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
      "logo_secundario" => "sometimes|mimes:jpeg,bmp,png",
      "logo_subtitulo" => "sometimes|mimes:jpeg,bmp,png",
      "logo_marca_agua" => "sometimes|mimes:jpeg,bmp,png",
      "logo_secundario" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['ticket']['width']},max_height={$this->logos_dimenciones['ticket']['height']}",
      "img_footer" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['footer']['width']},max_height={$this->logos_dimenciones['footer']['height']}",
    ];
  }
}