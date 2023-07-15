<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageBannerFooterSaveRequest extends FormRequest
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
      "img_footer" => "required|mimes:jpeg|dimensions:max_width={$this->logos_dimenciones['footer']['width']},max_height={$this->logos_dimenciones['footer']['height']}",
    ];
  }

  public function messages()
  {
    
    return [
      'img_footer.required' => 'La imagen banner del pie de pagina es requerida',
    ];
  }
}
